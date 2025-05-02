
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
    inputs = {
        lightThreshold,
        lightLevel,
        higherLightPower,
        fanThreshold,
        fanLevel,
        lowerFanPower
    };
    localStorage.setItem('inputs', JSON.stringify(inputs));

    localStorage.setItem('isLightAdjusted', NaN);
    localStorage.setItem('isFanAdjusted', NaN);

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
    const {
        lightThreshold,
        lightLevel,
        higherLightPower,
        fanThreshold,
        fanLevel,
        lowerFanPower
    } = inputs;

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
            body: new URLSearchParams({
                device: 'fan',
                value: slider.value
            })
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

        fetch('proxy.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                device: 'led',
                value: slider1.value
            })
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
        datasets: [{
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
                title: {
                    display: true,
                    text: 'Time'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Value'
                }
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

            if (toggleState) {
                handleLightAndFanSettings(Light, Temp);
            }

        })
        .catch(error => console.error('Error fetching sensor data:', error)); // Handle errors

}

// Initialize flags from localStorage or default to false

function handleLightAndFanSettings(Light, Temp) {
    let {
        lightThreshold: LightT,
        lightLevel: LightL,
        higherLightPower: HLightP,
        fanThreshold: FanT,
        fanLevel: FanL,
        lowerFanPower: LFanP
    } = inputs;
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
    console.log(lowerLight, higherLight, higherFan, lowerFan, Again);

    if (!isNaN(LightT) && !isNaN(LightL) && !isNaN(HLightP)) {

        if (lowerLight) {
            fetch('proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'led',
                    value: LightL
                })
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
            fetch('proxy.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'led',
                    value: HLightP
                })
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
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'fan',
                    value: FanL
                })
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
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    device: 'fan',
                    value: LFanP
                })
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
            const now = data.DateTime || 'N/A'; //new Date().toLocaleTimeString(); // Get current time

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
                realTimeChart.data.datasets[1].data.push(data.Luminosity || 0); // Add luminosity

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



window.addEventListener('resize', () => {
    realTimeChart.resize(); // Trigger Chart.js to resize dynamically
});
src = "https://code.jquery.com/jquery-3.6.0.min.js"
src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
window.addEventListener('resize', () => {
    realTimeChart.resize(); // Trigger Chart.js to resize dynamically
});
