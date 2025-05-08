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
<style>
    #navbar {
        position: relative;
        z-index: 10;
        /* Ensures it stays in front */
    }
</style>
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
                    console.log(`current time: ${currentTime}`);

                    data.forEach(event => {
                        console.log(`event time: ${event.Start_Time}`);
                        if (event.Start_Time === currentTime && event.Status === 'on') {
                            // Call the new function to turn on light and fan
                            console.log(`Event Matched: ${event.EID}, Start Time: ${event.Start_Time}, Status: ${event.Status}`);
                            turnOnLightAndFan(); // Turn on light and fan at level 100
                        }
                    });
                })
                .catch(error => console.error('Error fetching events:', error));
        }, 10000); // Check every 60 seconds
    });

    function turnOnLightAndFan() {
        console.log("Turning on light and fan at level 100");

        // Turn on light
        fetch('proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'led',
                    value: 100
                })
            })
            .then(() => {
                console.log("Light turned on at level 100");
                slider1.removeEventListener('input', handleSliderInput1);
                slider1.value = 100; // Update slider dynamically
                valueDisplay1.textContent = slider1.value;
                slider1.addEventListener('input', handleSliderInput1); // Re-add event listener
            })
            .catch(error => console.error('Error turning on light:', error));

        // Turn on fan
        fetch('proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'fan',
                    value: 100
                })
            })
            .then(() => {
                console.log("Fan turned on at level 100");
                slider.removeEventListener('input', handleSliderInput);
                slider.value = 100; // Update slider dynamically
                valueDisplay.textContent = slider.value;
                slider.addEventListener('input', handleSliderInput); // Re-add event listener
            })
            .catch(error => console.error('Error turning on fan:', error));
    }
</script>