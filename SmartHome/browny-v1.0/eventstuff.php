<?php
session_start();
#echo $_SESSION['uid'] ?? 'UID not set';
require_once 'Connection2.php'; // Use the DBConn class

$db = new DBConn();

$conn = $db->getConnection();
// Handle the toggle button status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['EID']) && isset($_POST['status'])) {
    $eventId = $_POST['EID'];
    $status = $_POST['status'];

    // Update the event status in the database
    $query = "UPDATE event SET Status = ? WHERE EID = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        exit;
    }
    $stmt->bind_param('si', $status, $eventId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update status']);
    }
    exit; // Stop further processing to prevent rendering the HTML
}
?>
<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

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
                            <h2 id="e-repeat">${event.ERepeat}</h2>
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
                Duration: formData.get('duration'),
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