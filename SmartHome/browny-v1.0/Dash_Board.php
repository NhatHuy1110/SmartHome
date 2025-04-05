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

    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




    <title>Dash Board</title>
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
                        <script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <section style="margin-top: 95px;"> <!-- Added margin-top to avoid overlap -->
        <!-- Display area -->
        <div class="data-container">
            <div class="data-square">
                <h3 class="data-label">Luminosity</h3>
                <p id="luminosity" class="data-value"></p>
            </div>
            <div class="data-square">
              <h3 class="data-label">Temperature</h3>
              <p id="temperature" class="data-value"></p>
            </div>
            <div class="data-square">
              <h3 class="data-label">Presence</h3>
              <p id="presence" class="data-value"></p>
            </div>
        </div>

        <!-- Add the container for the graph -->
        <div class="chartContainer">
            <canvas id="realTimeChart"></canvas>
        </div>        

    </section>


    <script>
        const ctx = document.getElementById('realTimeChart').getContext('2d');

        // Create the Chart.js line graph
        const realTimeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [], // Time labels will be added dynamically
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: [], // Temperature data will be added dynamically
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Luminosity (LUX)',
                        data: [], // Luminosity data will be added dynamically
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: false,
                        tension: 0.1
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        title: { display: true, text: 'Time' }
                    },
                    y: {
                        title: { display: true, text: 'Value' }
                    }
                }
            }
        });

    </script>


    <script>
        // Function to call fetchdata.php and log the response
        function callFetchData() {
            fetch('fetchdata.php') // Call fetchdata.php
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    console.log(data.message); // Log success message to the console
                })
                .catch(error => console.error('Error calling fetchdata.php:', error)); // Handle errors
        }

        // Function to fetch sensor data and dynamically update the elements
        function fetchLatestSensorData() {
            fetch('fetch_sensors.php') // Send AJAX request
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    // Dynamically update the display elements
                    document.getElementById('luminosity').textContent = (data.Luminosity || 'N/A') + ' LUX';
                    document.getElementById('temperature').textContent = (data.Temperature || 'N/A') + ' (°C)';

                    if (data.Presence === "0" || data.Presence === false) {
                        document.getElementById('presence').textContent = "No";
                    } else if (data.Presence === "1" || data.Presence === true) {
                        document.getElementById('presence').textContent = "Yes";
                    } else {
                        document.getElementById('presence').textContent = "N/A";
                    }
                })
                .catch(error => console.error('Error fetching sensor data:', error)); // Handle errors
        }

        let previousData = {
                        Temperature: null,
                        Luminosity: null
                    };

                    function updateChart() {
                        fetch('fetch_sensors.php') // Make a request to your backend
                            .then(response => response.json())
                            .then(data => {
                                const now = data.DateTime || 'N/A';//new Date().toLocaleTimeString(); // Get current time

                                // Check if the data has changed
                                if (
                                    data.Temperature !== previousData.Temperature ||
                                    data.Luminosity !== previousData.Luminosity
                                ) {
                                    realTimeChart.data.labels.push(now);

                                    // Limit the number of labels/data points
                                    if (realTimeChart.data.labels.length > 10) {
                                        realTimeChart.data.labels.shift();
                                        realTimeChart.data.datasets[0].data.shift();
                                        realTimeChart.data.datasets[1].data.shift();
                                    }

                                    // Update the datasets
                                    realTimeChart.data.datasets[0].data.push(data.Temperature || 0); // Add temperature
                                    realTimeChart.data.datasets[1].data.push(data.Luminosity || 0);  // Add luminosity

                                    // Update the chart
                                    realTimeChart.update();

                                    // Store current data for comparison in the next cycle
                                    previousData.Temperature = data.Temperature;
                                    previousData.Luminosity = data.Luminosity;
                                }
                            })
                            .catch(error => console.error('Error fetching sensor data:', error));
                    }

                    

        // Call fetchdata.php every 1.5 seconds
        setInterval(callFetchData, 1100);

        // Fetch sensor data every 2 seconds
        setInterval(fetchLatestSensorData, 1200);

        // Update chart every second
        setInterval(updateChart, 1200);
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $('.notification').load('Notification.php');
            $('.counter').text('0').hide();

            var counter = 0;
            let prevnotifnum = localStorage.getItem('prevnotifnum') || 0;

            function loadNotifications() {
                $.get('Notification.php', function(data) {
                    if (data) {
                        let notificationData = JSON.parse(data);
                        if (notificationData.length > 0 && notificationData.length > prevnotifnum) {
                            counter += notificationData.length - prevnotifnum;
                            prevnotifnum = notificationData.length
                            $('.counter').text(counter).show(); // Reveal red number if new notification not 0
                        }
                        // dropdown list
                        $('.notification').html('');
                        notificationData.forEach(function(item) {
                            $('.notification').append(`
                            <div class="dropdown-item">
                                <h6>${item.Error_Message}</h6>
                                <span>${item.DateTime}</span>
                                <hr class="mt-1 mb-1">
                            </div>
                        `);
                        });
                    }
                });
            }

            loadNotifications();

            setInterval(loadNotifications, 3000);

            $('#noti_count').on('click', function() {
                counter = 0;
                $('.counter').text('0').hide(); // Reset counter when clicked
            });
        });
    </script>


   

            


    <script>
        window.addEventListener('resize', () => {
        realTimeChart.resize(); // Trigger Chart.js to resize dynamically
        });
    </script>

    <style>
        .chartContainer {
            box-sizing: border-box; /* Includes padding and borders in width calculation */
            background-color: #ffffff; /* Solid black background */
            border: 2px solid #cccccc; /* Border around the box */
            border-radius: 10px; /* Rounded corners */
            width: 943px; /* Set fixed horizontal width */
            max-width: 100%; /* Ensure it scales down responsively */
            margin: auto; /* Center the box horizontally */
            height: 475px;
        }


        #realTimeChart {
            width: 100% !important; /* Fit the container horizontally */
            height: auto !important; /* Keep the aspect ratio */
        }

    </style>

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
