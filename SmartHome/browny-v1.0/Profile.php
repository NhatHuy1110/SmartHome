<!DOCTYPE html>
<html lang="en">
<?php 
session_start(); 
require 'Connection.php';
$conn = Connect();

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
    <link rel="shortcut icon" type="image/icon" href="assets/logo/favicon.png"/>

    <!-- Css for data display box -->
    <link rel="stylesheet" href="assets/css/DisplayBox.css">

    <!-- Font-awesome.min.css -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Flat icon css -->
    <link rel="stylesheet" href="assets/css/flaticon.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="assets/css/animate.css">

    <!-- Owl.carousel.css -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">

    <!-- Bootstrap.min.css -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Bootsnav -->
    <link rel="stylesheet" href="assets/css/bootsnav.css">

    <link rel="stylesheet" href="assets/css/user.css">

    <link rel="stylesheet" href="assets/css/viewshopdetails.css">

    <!-- Style.css -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Responsive.css -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="notifHandler.js"></script>
    <title>Profile</title>

</head>

<body>

   <div class="header-area">
        <!-- Start Navigation -->
        <nav class="navbar navbar-default bootsnav navbar-fixed dark no-background">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.php">Smart Home</a>
                </div><!--/.navbar-header-->
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class=" smooth-menu active"></li>
                        <li><a href="Dash_Board.php">Dash Board</a></li>
                        <li><a href="Event.php">Event</a></li>
                        <li><a href="Profile.php">Profile</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle text-light" id="noti_count" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
                                <span class="counter" style="position: absolute; top: -5px; right: -5px; background: red; color: white; font-size: 12px; padding: 2px 6px; border-radius: 50%;">0</span>
                                <i class="fas fa-bell" style="font-size: 20px;"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="notification" style="max-height: 200px; overflow-y: auto;"></div>
                            </div>
                        </li>
                    </ul><!--/.nav -->
                </div><!-- /.navbar-collapse -->
            </div><!--/.container-->
        </nav><!--/nav-->
        <!-- End Navigation -->
    </div><!--/.header-area-->

    <div class="clearfix"></div>




    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script>
    $(document).ready(function() {
        $('#navbar-menu').on('show.bs.collapse', function() {
            $(this).css('height', 'auto'); // Set height dynamically
        });
    });
    </script>


</body>
</html>
