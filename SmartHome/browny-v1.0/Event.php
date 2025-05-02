<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Event";
include 'head.php';
?>

<body>

    <?php
    include 'navbar.php';
    require_once 'eventstuff.php';
    ?>

    <div class="clearfix"></div>

    <div class="event-container">
        <h1>Schedule Management</h1>
        <button id="add-event-btn">Add New Event</button>
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

            <label for="duration">Duration (in minutes):</label>
            <input type="number" id="duration" name="duration" required />

            <label for="temp-upper">Temperature Upper:</label>
            <input type="number" id="temp-upper" name="temp-upper" required />

            <label for="temp-lower">Temperature Lower:</label>
            <input type="number" id="temp-lower" name="temp-lower" required />

            <label for="lum-upper">Luminosity Upper:</label>
            <input type="number" id="lum-upper" name="lum-upper" required />

            <label for="lum-lower">Luminosity Lower:</label>
            <input type="number" id="lum-lower" name="lum-lower" required />

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
            background-color: #e3e3e3;
            width: 50%;
            margin: 120px auto 0;
            padding: 20px;
            border: 1px solid black;
            border-radius: 10px;
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
            background-color: #fff;
            border: 1px solid #000;
            width: 80%;
            margin: 20px auto;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .constraint {
            display: flex;
            gap: 20px;
        }

        #event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 40px;
        }

        #event-header h2 {
            font-size: 30px;
            margin: 0;
        }

        .btn-div {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Toggle switch */
        #navbar {
            position: relative;
            z-index: 10;
            /* Ensures it stays in front */
        }

        .checkbox {
            display: none;
        }

        .button {
            width: 60px;
            height: 30px;
            background-color: #4b4b4b;
            border-radius: 30px;
            position: relative;
            transition: background-color 0.3s;
            border: 1px solid black;
        }

        .button::before {
            content: "";
            position: absolute;
            width: 24px;
            height: 24px;
            top: 2.5px;
            left: 3px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .checkbox:checked+.button {
            background-color: #5cf445;
        }

        .checkbox:checked+.button::before {
            transform: translateX(30px);
        }

        /* Edit icon */
        .edit-icon {
            color: #007bff;
            font-size: 20px;
            cursor: pointer;
            margin-left: 10px;
            transition: color 0.3s ease;
        }

        .edit-icon:hover {
            color: #0056b3;
        }

        /* Dialog styling */
        dialog {
            border: none;
            border-radius: 10px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5);
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