<?php
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
        <div class="start-time">Start time: ${event.Start_Time}</div>
        <div class="Duration">Duration: ${event.Duration} mins</div>
        <div class="btn-div"> 
            <i class="fa fa-edit edit-icon" onclick="editEvent(${event.EID}, '${event.EDate}', '${event.Start_Time}', ${event.Duration}, '${event.ERepeat}')"></i>
            <i class="fa fa-trash delete-icon" onclick="deleteEvent(${event.EID})"></i>
            <input type="checkbox" class="checkbox" id="${checkboxId}" onchange="updateStatus(${event.EID}, this.checked)" />
            <label for="${checkboxId}" class="button" id="${buttonId}"></label>
        </div>
    </div>
    <div class="event-date">Event date: ${event.EDate}</div>
    <h2 id="e-repeat">${event.ERepeat}</h2>
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

        // Open dialog
        addEventBtn.addEventListener('click', () => {
            addEventForm.reset();
            addEventForm.dataset.mode = 'add';
            addEventForm.dataset.eid = '';
            addEventForm.querySelector('h2').textContent = 'Add New Event';
            addEventForm.querySelector('button[type="submit"]').textContent = 'Add Event';
            dialog.showModal();
        });

        // Close dialog
        closeDialogBtn.addEventListener('click', () => dialog.close());

        // Handle submit
        addEventForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(addEventForm);
            const mode = addEventForm.dataset.mode;
            const eventData = {
                EDate: formData.get('event-date'),
                Start_time: formData.get('start-time'),
                Duration: formData.get('duration'),
                ERepeat: formData.get('e-repeat'),
            };

            let url = '';
            if (mode === 'add') {
                url = 'add_event.php';
            } else {
                eventData.EID = addEventForm.dataset.eid;
                url = 'update_event.php';
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(eventData),
                })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        alert(`${mode === 'add' ? 'Added' : 'Updated'} successfully!`);
                        dialog.close();
                        fetchDataEvents();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });
    });
</script>
<script>
    function editEvent(EID, date, startTime, duration, repeat) {
        const dialog = document.getElementById('add-event-dialog');
        const addEventForm = document.getElementById('add-event-form');

        addEventForm.reset();
        addEventForm.dataset.mode = 'edit';
        addEventForm.dataset.eid = EID;

        addEventForm.querySelector('h2').textContent = 'Edit Event';
        addEventForm.querySelector('button[type="submit"]').textContent = 'Save Changes';

        addEventForm['event-date'].value = date;
        addEventForm['start-time'].value = startTime;
        addEventForm['duration'].value = duration;
        addEventForm['e-repeat'].value = repeat;

        dialog.showModal();
    }

    function deleteEvent(EID) {
        if (!confirm("Are you sure you want to delete this event?")) return;

        fetch('delete_event.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    EID: EID
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Event deleted successfully!');
                    fetchDataEvents();
                } else {
                    alert('Delete failed: ' + data.message);
                }
            });
    }
</script>