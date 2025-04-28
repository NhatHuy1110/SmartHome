<!DOCTYPE html>
<html lang="en">
<?php 
session_start(); 
require 'Connection.php';
$conn = Connect();
$api_key = "aio_itBH53rzbfnwfhiabK1R9p2mAOKx"; // Replace with your Adafruit IO API Key
$feed_url = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/fan-control";
$feed_url1 = "https://io.adafruit.com/api/v2/anhtanggroup1/feeds/led";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-AIO-Key: $api_key"));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error fetching data: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);
    $latest_value = $data['last_value']; // Assuming 'last_value' holds the latest feed value
}

curl_setopt($ch, CURLOPT_URL, $feed_url1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-AIO-Key: $api_key"));

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error fetching data: ' . curl_error($ch);
} else {
    $data = json_decode($response, true);
    $latest_value1 = $data['last_value']; // Assuming 'last_value' holds the latest feed value
}

curl_close($ch);
?>

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

    <section> <!-- Added margin-top to avoid overlap -->
        <!-- Display area -->

        <div class="page">
            <!-- Left side: Data boxes and chart -->
            <div class="left-section">
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
                <div class="chartContainer">
                    <canvas id="realTimeChart"></canvas>
                </div>

                <div class="chartContainer">
                    <label>Select an Option:</label>
                    <select id="menu-range" name="menu" onchange="handleDropdownChange()">
                        <option value="Week">7 Days</option>
                        <option value="Month">30 Days</option>
                    </select>
                    <canvas id="visualTemp"></canvas>
                </div>

            </div>

            <!-- Right side: Another section -->
            <div class="right-section">
                <div class="box">
                    <h3>LED Control</h3>
                    <div class="slider-container">
                        <input type="range" class="slider" id="powerSlider1" min="0" max="100" step="5" value="<?php echo $latest_value1; ?>">
                        <div class="value-display">
                            LED Power: <span id="sliderValue1"><?php echo $latest_value1; ?></span>%
                        </div>
                    </div>
                </div>
                <div class="box">
                    <h3>Fan Control</h3>
                    <div class="slider-container">
                        <input type="range" class="slider" id="powerSlider" min="0" max="100" step="5" value="<?php echo $latest_value; ?>">
                        <div class="value-display">
                            Fan Power: <span id="sliderValue"><?php echo $latest_value; ?></span>%
                        </div>
                    </div>
                </div>
                <div class="box">
                    <h3>Automation</h3>
                    <div class="toggle-row">
                        <span>Automatic:</span>
                        <label class="switch">
                            <input type="checkbox" id="automaticToggle">
                            <span class="slider1 round"></span>
                        </label>
                        <button id="resetButton">Reset</button>
                    </div>
                    <div id="inputBoxContainer" class="hidden">
                        <h4>Input Thresholds</h4>
                        <div class="input-group">
                            <label for="lightThreshold">Light Threshold:</label>
                            <input type="number" id="lightThreshold" placeholder="Enter light threshold">
                        </div>
                        <div class="input-group">
                            <label for="lightLevel">Lower LED Power:</label>
                            <input type="number" id="lightLevel" placeholder="Enter lower LED Power">
                        </div>
                        <div class="input-group">
                            <label for="higherLightPower">Higher LED Power:</label>
                            <input type="number" id="higherLightPower" placeholder="Enter higher LED power">
                        </div>
                        <div class="input-group">
                            <label for="fanThreshold">Temperature Threshold:</label>
                            <input type="number" id="fanThreshold" placeholder="Enter temperature threshold">
                        </div>
                        <div class="input-group">
                            <label for="fanLevel">Higher Fan Power:</label>
                            <input type="number" id="fanLevel" placeholder="Enter higher fan power">
                        </div>
                        <div class="input-group">
                            <label for="lowerFanPower">Lower Fan Power:</label>
                            <input type="number" id="lowerFanPower" placeholder="Enter lower fan power">
                        </div>
                        <div class="button-row">
                            <button id="confirmButton">Confirm</button>
                        </div>
                    </div>
                    <div id="inputDisplay"></div>


                </div>
                
            </div>
        </div>


    </section>

    <script>
        // Initialize elements
        const automaticToggle = document.getElementById('automaticToggle');
        const resetButton = document.getElementById('resetButton');
        const inputBoxContainer = document.getElementById('inputBoxContainer');
        const inputDisplay = document.getElementById('inputDisplay');
        const confirmButton = document.getElementById('confirmButton');

        let inputs = {}; // To store input values

        // Load saved state and inputs from localStorage on page load
        window.addEventListener('load', () => {
            const savedInputs = JSON.parse(localStorage.getItem('inputs'));
            const toggleState = localStorage.getItem('automaticToggleState') === 'true'; // Retrieve toggle state
            automaticToggle.checked = toggleState; // Restore toggle state

            if (savedInputs) {
                inputs = savedInputs;
                displayInputs(); // Always display inputs if they exist
            }

            // Hide the input box if the toggle is off
            if (!toggleState) {
                inputBoxContainer.classList.add('hidden');
            }
        });

        // Toggle "Automatic" button
        automaticToggle.addEventListener('change', () => {
            if (automaticToggle.checked) {
                localStorage.setItem('automaticToggleState', 'true'); // Save toggle state as ON
                localStorage.setItem('autoAgain', 'true');
                if (Object.keys(inputs).length === 0) {
                    inputBoxContainer.classList.remove('hidden'); // Show input box if no inputs exist
                }
            } else {
                localStorage.setItem('automaticToggleState', 'false'); // Save toggle state as OFF
                inputBoxContainer.classList.add('hidden'); // Hide input box
            }
        });

        // Confirm Button functionality
        confirmButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent clicking outside
            const lightThreshold = document.getElementById('lightThreshold').value;
            const lightLevel = document.getElementById('lightLevel').value;
            const higherLightPower = document.getElementById('higherLightPower').value;
            const fanThreshold = document.getElementById('fanThreshold').value;
            const fanLevel = document.getElementById('fanLevel').value;
            const lowerFanPower = document.getElementById('lowerFanPower').value;


            // Save inputs to object and localStorage
            inputs = { lightThreshold, lightLevel,higherLightPower, fanThreshold, fanLevel ,lowerFanPower};
            localStorage.setItem('inputs', JSON.stringify(inputs));

            localStorage.setItem('isLightAdjusted',NaN);
            localStorage.setItem('isFanAdjusted',NaN);

            // Hide input box and display inputs
            inputBoxContainer.classList.add('hidden');
            displayInputs();
        });

        // Reset Button functionality
        resetButton.addEventListener('click', () => {
            // Clear inputs and localStorage
            inputs = {};
            localStorage.removeItem('inputs');
            localStorage.removeItem('automaticToggleState'); // Clear toggle state
            localStorage.removeItem('isLightAdjusted');
            localStorage.removeItem('isFanAdjusted');
            localStorage.removeItem('autoAgain');
            

            // Remove displayed inputs and reset toggle
            inputDisplay.innerHTML = '';
            automaticToggle.checked = false;

            // Hide the input box
            inputBoxContainer.classList.add('hidden');
        });

        // Function to display inputs
        function displayInputs() {
            const { lightThreshold, lightLevel, higherLightPower, fanThreshold, fanLevel, lowerFanPower} = inputs;

            if (Object.keys(inputs).length > 0) {
                inputDisplay.innerHTML = `
                    <div class="input-summary">
                        <h4>Input Summary</h4>
                        <table>
                            <tr>
                                <th>Device</th>
                                <th>Threshold</th>
                                <th>Lower Power</th>
                                <th>Higher Power</th>
                            </tr>
                            <tr>
                                <th>LED</th>
                                <th>${lightThreshold}</th>
                                <th>${lightLevel}</th>
                                <th>${higherLightPower}</th>
                            </tr>
                            <tr>
                                <th>Fan</th>
                                <th>${fanThreshold}</th>
                                <th>${lowerFanPower}</th>
                                <th>${fanLevel}</th>
                            </tr>
                        </table> 
                    </div>
                `;
            }
        }


        const slider = document.getElementById('powerSlider');
        const valueDisplay = document.getElementById('sliderValue');

        // Update the displayed value in real-time
        let debounceTimer;
        function handleSliderInput() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                valueDisplay.textContent = slider.value;

                fetch('proxy.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ value: slider.value })
                })
                .then(response => {
                    if (response.ok) {
                        return response.text(); // Parse the response
                    } else {
                        console.error('Error updating Fan Power:', response.statusText);
                    }
                })
                .then(data => {
                    console.log('Server response:', data);
                })
                .catch(error => console.error('Error sending data to Adafruit IO:', error));

            }, 300); // Delay fetch requests by 300ms
        }
        // add event listener
        slider.addEventListener('input', handleSliderInput);

        const slider1 = document.getElementById('powerSlider1');
        const valueDisplay1 = document.getElementById('sliderValue1');

        // Update the displayed value in real-time
        let debounceTimer1;
        function handleSliderInput1() {
            clearTimeout(debounceTimer1);
            debounceTimer1 = setTimeout(() => {
                valueDisplay1.textContent = slider1.value;

                fetch('proxy1.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({ value: slider1.value })
                })
                .then(response => {
                    if (response.ok) {
                        return response.text(); // Parse the response
                    } else {
                        console.error('Error updating Fan Power:', response.statusText);
                    }
                })
                .then(data => {
                    console.log('Server response:', data);
                })
                .catch(error => console.error('Error sending data to Adafruit IO:', error));

            }, 300); // Delay fetch requests by 300ms
        }
        // add event listener
        slider1.addEventListener('input', handleSliderInput1);

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

                if (data.Presence === "0") {
                    document.getElementById('presence').textContent = "No";
                } else if (data.Presence === "1") {
                    document.getElementById('presence').textContent = "Yes";
                } else {
                    document.getElementById('presence').textContent = "N/A";
                }

                let Light = Number(data.Luminosity);
                let Temp = Number(data.Temperature);
                let toggleState = localStorage.getItem('automaticToggleState') === 'true'; // Retrieve toggle state

                if(toggleState){
                  handleLightAndFanSettings(Light, Temp);
                }

            })
            .catch(error => console.error('Error fetching sensor data:', error)); // Handle errors
            
        }

        // Initialize flags from localStorage or default to false

        function handleLightAndFanSettings(Light, Temp) {
            let { lightThreshold: LightT, lightLevel: LightL, higherLightPower:HLightP, fanThreshold: FanT, fanLevel: FanL, lowerFanPower:LFanP } = inputs;
//             const { lightThreshold, lightLevel, higherLightPower, fanThreshold, fanLevel, lowerFanPower} = inputs;
            LightT = Number(LightT);
            LightL = Number(LightL);
            HLightP = Number(HLightP);
            FanT = Number(FanT);
            FanL = Number(FanL);
            LFanP = Number(LFanP);

            let LightAdjustState = localStorage.getItem('isLightAdjusted') === 'true'; // Retrieve light state
            let LightAdjustNaN = localStorage.getItem('isLightAdjusted') === "NaN";
            let FanAdjustState = localStorage.getItem('isFanAdjusted') === 'true'; // Retrieve fan state
            let FanAdjustNaN = localStorage.getItem('isFanAdjusted') === "NaN";
            let Again = localStorage.getItem('autoAgain') === 'true';
            let lowerLight = Light <= LightT && (!LightAdjustState || LightAdjustNaN || Again);
            let higherLight = Light > LightT && (LightAdjustState || LightAdjustNaN || Again);
            let higherFan = Temp >= FanT && (!FanAdjustState || FanAdjustNaN || Again);
            let lowerFan = Temp < FanT && (FanAdjustState || FanAdjustNaN || Again);
            console.log(lowerLight, higherLight,higherFan, lowerFan, Again);

            if (!isNaN(LightT) && !isNaN(LightL) && !isNaN(HLightP)) {

                if (lowerLight) {
                    fetch('proxy1.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ value: LightL })
                    })
                    .then(() => {
                        console.log("Light adjusted to level:", LightL);
                        slider1.removeEventListener('input', handleSliderInput1);
                        // Update slider dynamically
                        slider1.value = LightL;
                        valueDisplay1.textContent = slider1.value;
                        localStorage.setItem('isLightAdjusted', 'true'); // Persist flag
                        localStorage.setItem('autoAgain', 'false');
                        //add back event listener
                        slider1.addEventListener('input', handleSliderInput1);
                    });
                } else if (higherLight) {
                    fetch('proxy1.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ value: HLightP })
                    })
                    .then(() => {
                        console.log("Light adjusted to level:", HLightP);
                        // Temporarily disable the listener
                        slider1.removeEventListener('input', handleSliderInput1);
                        // Update slider dynamically
                        slider1.value = HLightP;
                        valueDisplay1.textContent = slider1.value;
                        localStorage.setItem('isLightAdjusted', 'false'); // Persist flag
                        localStorage.setItem('autoAgain', 'false');
                        //add back event listener
                        slider1.addEventListener('input', handleSliderInput1);
                    });
                }
            }

            if (!isNaN(FanT) && !isNaN(FanL) && !isNaN(LFanP)) {
                if (higherFan) {
                    fetch('proxy.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ value: FanL })
                    })
                    .then(() => {
                        console.log("Fan adjusted to level:", FanL);
                        slider.removeEventListener('input', handleSliderInput);
                        // Update slider dynamically
                        slider.value = FanL;
                        valueDisplay.textContent = slider.value;
                        localStorage.setItem('isFanAdjusted', 'true'); // Persist flag
                        localStorage.setItem('autoAgain', 'false');
                        //add back event listener
                        slider.addEventListener('input', handleSliderInput);
                    });
                } else if (lowerFan) {
                    fetch('proxy.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ value: LFanP })
                    })
                    .then(() => {
                        console.log("Fan adjusted to level:", LFanP);
                        slider.removeEventListener('input', handleSliderInput);
                        // Update slider dynamically
                        slider.value = LFanP;
                        valueDisplay.textContent = slider.value;
                        localStorage.setItem('isFanAdjusted', 'false'); // Persist flag
                        localStorage.setItem('autoAgain', 'false');
                        //add back event listener
                        slider.addEventListener('input', handleSliderInput);
                    });
                }
            }
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

        function fetchHistoryTemp() {
            const dateStart = new Date();
            const range = document.getElementById('menu-range').value;

            // Adjust the start date based on the selected range
            if (range === 'Week') {
                dateStart.setDate(dateStart.getDate() - 7); // Subtract 7 days
            } else if (range === 'Month') {
                dateStart.setDate(dateStart.getDate() - 30); // Subtract 30 days
            }
            const formattedDateStart = dateStart.toISOString().split('T')[0]; // Format as YYYY-MM-DD

            const dateEnd = new Date();
            dateEnd.setDate(dateEnd.getDate() - 1); // Subtract 1 day for the end date
            const formattedDateEnd = dateEnd.toISOString().split('T')[0]; // Format as YYYY-MM-DD

            // Construct the POST body with parameters
            const postBody = `dateStart=${formattedDateStart}&dateEnd=${formattedDateEnd}`;

            // Send the POST request
            fetch('fetch_history.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: postBody, // Send the constructed parameters
                })
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    console.log('Historical Data:', data); // Log the response data

                    // Calculate daily averages
                    const averages = calculateDailyAverages(data);
                    console.log('Daily Averages:', averages);

                    // Extract labels (dates) and datasets (temperature and luminosity averages)
                    const labels = averages.map(avg => avg.date);
                    const temperatureData = averages.map(avg => avg.averageTemperature);
                    const luminosityData = averages.map(avg => avg.averageLuminosity);

                    // Update the chart with the calculated data
                    const ctx = document.getElementById('visualTemp').getContext('2d');
                    new Chart(ctx, {
                        type: 'line', // Line chart
                        data: {
                            labels: labels, // Dates
                            datasets: [{
                                    label: 'Average Temperature (°C)',
                                    data: temperatureData, // Average temperatures
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    fill: false,
                                    tension: 0.1
                                },
                                {
                                    label: 'Average Luminosity (LUX)',
                                    data: luminosityData, // Average luminosities
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    fill: false,
                                    tension: 0.1
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Daily Averages of Temperature and Luminosity'
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Value'
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching historical data:', error)); // Handle errors
        }

        function calculateDailyAverages(data) {
            const groupedData = {};

            // Group temperatures and luminosities by date
            data.forEach(record => {
                const date = record.DateTime.split(' ')[0]; // Extract the date (YYYY-MM-DD)
                if (!groupedData[date]) {
                    groupedData[date] = {
                        temperatures: [],
                        luminosities: []
                    };
                }
                groupedData[date].temperatures.push(Number(record.Temperature)); // Ensure Temperature is a number
                groupedData[date].luminosities.push(Number(record.Luminosity)); // Ensure Luminosity is a number
            });

            console.log(`Grouped Data:`, groupedData);

            // Calculate the average temperature and luminosity for each date
            const dailyAverages = Object.entries(groupedData).map(([date, values]) => {
                const totalTemperature = values.temperatures.reduce((sum, temp) => sum + temp, 0); // Sum all temperatures
                const averageTemperature = totalTemperature / values.temperatures.length; // Calculate the average temperature

                const totalLuminosity = values.luminosities.reduce((sum, lum) => sum + lum, 0); // Sum all luminosities
                const averageLuminosity = totalLuminosity / values.luminosities.length; // Calculate the average luminosity

                return {
                    date,
                    averageTemperature: averageTemperature.toFixed(2), // Round to 2 decimal places
                    averageLuminosity: averageLuminosity.toFixed(2) // Round to 2 decimal places
                };
            });

            return dailyAverages;
        }
       
        // Call fetchdata.php every 1.5 seconds
        setInterval(callFetchData, 1500);

        // Fetch sensor data every 2 seconds
        setInterval(fetchLatestSensorData, 1600);

        // Update chart every second
        setInterval(updateChart, 1600);

        fetchHistoryTemp();

    </script>


    <!-- Include jQuery and Bootstrap JS -->
   
	<script>
        window.addEventListener('resize', () => {
        realTimeChart.resize(); // Trigger Chart.js to resize dynamically
        });
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
        window.addEventListener('resize', () => {
        realTimeChart.resize(); // Trigger Chart.js to resize dynamically
        });
        $(document).ready(function() {
            $('#navbar-menu').on('show.bs.collapse', function() {
                $(this).css('height', 'auto'); // Set height dynamically
            });
        });
    </script>


</body>

</html>