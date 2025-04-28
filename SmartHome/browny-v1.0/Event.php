<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require 'Connection.php';
$conn = Connect();

// Handle the toggle button status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EID']) && isset($_POST['status'])) {
    $eventId = $_POST['EID'];
    $status = $_POST['status'];

    // Update the event status in the database
    $query = "UPDATE event SET Status = ? WHERE EID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $status, $eventId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
    exit; // Stop further processing to prevent rendering the HTML
}
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/6b23de7647.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="notifHandler.js"></script>
    <title>Event</title>
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

    <div class="event-container">
        <h1>Schedule Management</h1>
        <button id="add-event-btn">Add New Event</button> <!-- Button to open the dialog -->
        <div id="event-dashboard"></div>
    </div>

    <dialog id="add-event-dialog">
        <form id="add-event-form">
            <h2>Add New Event</h2>
            <label for="event-name">Event Name:</label>
            <input type="text" id="event-name" name="event-name" required />

            <label for="event-date">Event Date:</label>
            <input type="date" id="event-date" name="event-date" required />

            <label for="start-time">Start Time:</label>
            <input type="time" id="start-time" name="start-time" required />

            <label for="temp-upper">Temperature Upper:</label>
            <input type="number" id="temp-upper" name="temp-upper" required />

            <label for="temp-lower">Temperature Lower:</label>
            <input type="number" id="temp-lower" name="temp-lower" required />

            <label for="lum-upper">Luminosity Upper:</label>
            <input type="number" id="lum-upper" name="lum-upper" required />

            <label for="lum-lower">Luminosity Lower:</label>
            <input type="number" id="lum-lower" name="lum-lower" required />

            <!-- New ERepeat Field -->
            <label for="e-repeat">Repeat:</label>
            <select id="e-repeat" name="e-repeat" required>
                <option value="Daily">Daily</option>
                <option value="Weekly">Weekly</option>
                <option value="Monthly">Monthly</option>
            </select>

            <div class="dialog-buttons">
                <button type="submit">Add Event</button>
                <button type="button" id="close-dialog-btn">Cancel</button>
            </div>
        </form>
    </dialog>

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

    <script>
        function fetchDataEvents() {
            fetch('fetch_events.php') // Fetch events from the backend
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    const eventContainer = document.getElementById('event-dashboard');
                    eventContainer.innerHTML = ''; // Clear existing events

                    data.forEach((event, index) => {
                        // Create a new event block
                        const eventBlock = document.createElement('div');
                        eventBlock.className = 'event-blocks';

                        // Generate unique IDs for the checkbox and label
                        const checkboxId = `checkbox-${index}`;
                        const buttonId = `button-${index}`;

                        // Add event details
                        eventBlock.innerHTML = `
                            <div id="event-header">
                                <h2>${event.EName}</h2>
                                <div class="btn-div"> 
                                    <i class="fa fa-edit edit-icon" onclick="editEvent(${event.EID})"></i>
                                    <input type="checkbox" class="checkbox" id="${checkboxId}" 
                                        onchange="updateStatus(${event.EID}, this.checked)" />
                                    <label for="${checkboxId}" class="button" id="${buttonId}"></label>
                                </div>
                            </div>
                            <div class="event-date">Event date: ${event.EDate}</div>
                            <div class="start-time">Start time: ${event.Start_time}</div>
                            <h2 id="erepeat">${event.ERepeat}</h2>
                            <div class="constraint">
                                <p>Temperature upper: ${event.Temp_Upper}</p>
                                <p>Temperature lower: ${event.Temp_Lower}</p>
                                <p>Lumid upper: ${event.Lum_Upper}</p>
                                <p>Lumid lower: ${event.Lum_Lower}</p>
                            </div>
                        `;
                        eventContainer.appendChild(eventBlock);

                        // Set the status of event on/off
                        const event_status = document.getElementById(checkboxId);
                        if (event.Status === 'on')
                            event_status.checked = true
                    });
                })
                .catch(error => console.error('Error fetching events:', error)); // Handle errors
        }

        // Function to send the toggle status to PHP
        function updateStatus(EID, isChecked) {
            const status = isChecked ? 'on' : 'off'; // Determine the status
            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `EID=${EID}&status=${status}`, // Send eventId and status as form data
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Status updated:', data); // Log the response from the server
                })
                .catch(error => console.error('Error updating status:', error)); // Handle errors
        }

        // Automatically fetch and update events every 5 seconds
        setInterval(fetchDataEvents, 3000);

        // Fetch events when the page loads
        window.onload = fetchDataEvents;
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dialog = document.getElementById('add-event-dialog');
            const addEventBtn = document.getElementById('add-event-btn');
            const closeDialogBtn = document.getElementById('close-dialog-btn');
            const addEventForm = document.getElementById('add-event-form');

            // Open the dialog when the "Add New Event" button is clicked
            addEventBtn.addEventListener('click', () => {
                dialog.showModal();
            });

            // Close the dialog when the "Cancel" button is clicked
            closeDialogBtn.addEventListener('click', () => {
                dialog.close();
            });

            // Handle form submission
            addEventForm.addEventListener('submit', (e) => {
                e.preventDefault(); // Prevent the default form submission

                // Collect form data
                const formData = new FormData(addEventForm);
                const eventData = {
                    EName: formData.get('event-name'),
                    EDate: formData.get('event-date'),
                    Start_time: formData.get('start-time'),
                    Temp_Upper: formData.get('temp-upper'),
                    Temp_Lower: formData.get('temp-lower'),
                    Lum_Upper: formData.get('lum-upper'),
                    Lum_Lower: formData.get('lum-lower'),
                    ERepeat: formData.get('e-repeat'), // Include the ERepeat field
                };

                // Send the data to the server
                fetch('add_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(eventData),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            alert('Event added successfully!');
                            dialog.close(); // Close the dialog
                            fetchDataEvents(); // Refresh the event list
                        } else if (data.duplicate) {
                            alert('An event with the same name and date already exists!');
                        } else {
                            alert('Failed to add event: ' + data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error adding event:', error);
                    });
            });
        });
    </script>

    <style>
        .event-container {
            background-color: rgb(227, 227, 227);
            width: 50%;
            border: 1px solid black;
            margin: auto;
            margin-top: 120px;
            border-radius: 10px;
            padding: 20px;
        }

        .event-container h1 {
            font-size: 2em;
            margin-bottom: 20px;
            color: black;
        }

        button {
            cursor: pointer;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
        }

        button:hover {
            background-color: #0056b3;
        }

        .event-blocks {
            background-color: rgb(255, 255, 255);
            border: 1px solid rgb(0, 0, 0);
            width: 80%;
            margin: auto;
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .constraint {
            display: flex;
            gap: 20px;
        }

        #event-header {
            display: flex;
            justify-content: space-between;
            /* Space between the event name and controls */
            align-items: center;
            /* Vertically align items */
            height: 40px;
        }

        #event-header h2 {
            font-size: 30px;
            margin: 0;
            /* Remove default margin */
        }

        .btn-div {
            display: flex;
            /* Use flexbox for horizontal alignment */
            align-items: center;
            /* Vertically align items */
            gap: 10px;
            /* Add spacing between the edit icon and toggle button */
        }

        .button {
            background-color: rgb(75, 75, 75);
            width: 90px;
            height: 40px;
            border: 1px solid black;
            border-radius: 30px;
            cursor: pointer;
            position: relative;
        }

        .button::before {
            position: absolute;
            width: 30px;
            height: 30px;
            background-color: white;
            content: "";
            border-radius: 50px;
            margin: 5px;
            transition: 0.3s;
        }

        input:checked+.button {
            background-color: rgb(92, 244, 69);
        }

        input:checked+.button::before {
            transform: translateX(50px);
        }

        .checkbox {
            display: none;
        }

        .edit-icon {
            color: #007bff;
            /* Blue color for the icon */
            font-size: 20px;
            /* Icon size */
            cursor: pointer;
            /* Pointer cursor on hover */
            margin-left: 10px;
            /* Add spacing */
            transition: color 0.3s ease;
            /* Smooth color transition */
        }

        .edit-icon:hover {
            color: #0056b3;
            /* Darker blue on hover */
        }

        dialog {
            border: none;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            position: fixed;
            /* Position the dialog relative to the viewport */
            top: 50%;
            /* Center vertically */
            left: 50%;
            /* Center horizontally */
            transform: translate(-50%, -50%);
            /* Adjust for the dialog's own dimensions */
            background-color: white;
            /* Ensure the background is visible */
            z-index: 1000;
            /* Ensure it appears above other elements */
        }

        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            /* Add a semi-transparent background */
        }

        dialog form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .dialog-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
    </style>

</body>

</html>