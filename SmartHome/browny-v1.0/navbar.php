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
                        <li><a href="Dash_Board.php">Dash Board</a></li>
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
    });
</script>