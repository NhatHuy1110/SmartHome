<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentFile = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['login_customer']) && $currentFile != 'index.php') {
    header("Location: index.php");
    exit();
}
?>
<div id="navbar">
    <div class="header-area">
        <nav class="navbar navbar-default bootsnav navbar-fixed dark no-background">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.php">Smart Home</a>
                </div>
                <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                        <li><a href="dashdisplay.php">Dash Board</a></li>

                        <li><a href="log.php">Log</a></li>
                        <li><a href="Event.php">Event</a></li>
                        <li><a href="Profile.php">Profile</a></li>
                        <?php if (isset($_SESSION['login_customer'])): ?>
                            <li><a href="Logout.php">Logout</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle text-light" id="numUnseen" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
                                    <!-- The counter element -->
                                    <span class="counter" style="position: absolute; top: +23px; right: +15px; background: red; color: white; font-size: 12px; padding: 2px 6px; border-radius: 50%;">0</span>
                                    <i class="fas fa-bell" style="font-size: 20px;"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="notification" style="max-height: 200px; overflow-y: auto;"></div>
                                </div>
                            </li>
                        <?php else: ?>
                            <li><a href="Login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#navbar-menu').on('show.bs.collapse', function() {
            $(this).css('height', 'auto');
        });
        localStorage.setItem('eventFlag', 'true');
        // Check every minute for matching events
        setInterval(() => {
            fetch('fetch_events.php') // Fetch events again
                .then(response => response.json())
                .then(data => {
                    console.log(data);

                    // Get the current time in HH:mm:ss format with seconds set to 00
                    const now = new Date();
                    now.setSeconds(0, 0); // Set seconds and milliseconds to 0
                    const currentTime = now.toTimeString().split(' ')[0]; // Format as HH:mm:ss

                    let EFlag = localStorage.getItem('eventFlag') === 'true'; // Retrieve event flag state

                    data.forEach(event => {
                        // Convert event.Start_Time (HH:MM:SS) into hours, minutes, and seconds
                        let [hours, minutes, seconds] = event.Start_Time.split(':').map(Number);

                        // Add duration to minutes
                        minutes += event.Duration;

                        // Handle overflow if minutes exceed 60
                        if (minutes >= 60) {
                            hours += Math.floor(minutes / 60); // Add extra hours
                            minutes = minutes % 60; // Keep remaining minutes
                        }

                        // Format back to HH:MM:SS
                        let endTime = 
                            String(hours).padStart(2, '0') + ':' + 
                            String(minutes).padStart(2, '0') + ':' + 
                            String(seconds).padStart(2, '0');

                        if (event.Start_Time === currentTime && event.Status === 'on' && EFlag ) {
                            // Call the new function to turn on light and fan
                            console.log(`Event Matched: ${event.EID}, Start Time: ${event.Start_Time}, Status: ${event.Status}`);
                            turnLightAndFan(100); // Turn on light and fan at level 100
                            localStorage.setItem('eventFlag', 'false');
                        }
                        if (endTime <= currentTime && event.Status === 'on' && !EFlag ) {
                            // Call the new function to turn on light and fan
                            console.log(`Event Matched: ${event.EID}, Start Time: ${event.Start_Time}, Status: ${event.Status}`);
                            turnLightAndFan(0); // Turn on light and fan at level 0
                            localStorage.setItem('eventFlag', 'true');
                        }
                    });
                })
                .catch(error => console.error('Error fetching events:', error));
        }, 10000); // Check every 60 seconds
    });

    function turnLightAndFan(power) {
        console.log("Turning on light and fan at level", power);
        
        // Turn on light
        fetch('sendLed.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    value: power
                })
            })
            .then(() => {
                console.log("Light turned at level", power);
            })
            .catch(error => console.error('Error turning light:', error));

        // Turn on fan
        fetch('sendFan.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    value: power
                })
            })
            .then(() => {
                console.log("Fan turned", power);
            })
            .catch(error => console.error('Error turning fan:', error));
    }

   
</script>