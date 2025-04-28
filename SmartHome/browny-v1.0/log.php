<?php
// Establish database connection
$mysqli = new mysqli("localhost:3307", "root", "", "SmartHome");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch data from Sensors table
$sensorsQuery = "SELECT * FROM Sensors ORDER BY DateTime DESC LIMIT 10";
$sensorsResult = $mysqli->query($sensorsQuery);

// Fetch data from Fan table
$fanQuery = "SELECT * FROM Fan ORDER BY DateTime DESC LIMIT 10";
$fanResult = $mysqli->query($fanQuery);

// Fetch data from Light table
$lightQuery = "SELECT * FROM Light ORDER BY DateTime DESC LIMIT 10";
$lightResult = $mysqli->query($lightQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
    <link rel="shortcut icon" type="image/icon" href="assets/logo/favicon.png" />

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

    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="notifHandler.js"></script>

    <title>Database Logs</title>
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
                        <li><a href="log.php">Log</a></li>
                        <li><a href="Event.php">Event</a></li>
                        <li><a href="Profile.php">Profile</a></li>
                        <li><a href="Logout.php">Logout</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle text-light" id="numUnseen" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative;">
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

    <div class="container" style = "margin-top: 100px;">
        <h2>Sensors Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>DateTime</th>
                    <th>Luminosity</th>
                    <th>Temperature</th>
                    <th>Presence</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sensorsResult->num_rows > 0) {
                    while ($row = $sensorsResult->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Luminosity']}</td>
                                <td>{$row['Temperature']}</td>
                                <td>" . ($row['Presence'] ? 'Yes' : 'No') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Fan Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>FID</th>
                    <th>DateTime</th>
                    <th>Fan Speed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($fanResult->num_rows > 0) {
                    while ($row = $fanResult->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['FID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Fan_Speed']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Light Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>RID</th>
                    <th>LID</th>
                    <th>DateTime</th>
                    <th>Intensity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($lightResult->num_rows > 0) {
                    while ($row = $lightResult->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['RID']}</td>
                                <td>{$row['LID']}</td>
                                <td>{$row['DateTime']}</td>
                                <td>{$row['Intensity']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
// Close the database connection
$mysqli->close();
?>
