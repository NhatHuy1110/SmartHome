<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Event";
include 'head.php';
?>

<body>

    <?php include 'navbar.php';
    require_once 'eventstuff.php';
    ?>

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

            <label for="duration">Duration(in minutes):</label>
            <input type="number" id="duration" name="duration" required />

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