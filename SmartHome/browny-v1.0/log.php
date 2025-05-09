<?php
// Include the Connection2.php file
require_once 'Connection2.php';

// Create an instance of DBConn
$db = new DBConn();

// Check if the request is for JSON data
if (isset($_GET['fetchData']) && $_GET['fetchData'] === 'true') {
    // Fetch all data from Sensors, Fan, and Light tables
    $sensorsData = $db->selectWhere('Sensors', [], 'DateTime', 0, 'DESC', '', ['DateTime', 'Luminosity', 'Temperature', 'Presence']);
    $fanData = $db->selectWhere('Fan', [], 'DateTime', 0, 'DESC', '', ['DateTime', 'Fan_Speed']);
    $lightData = $db->selectWhere('Light', [], 'DateTime', 0, 'DESC', '', ['DateTime', 'Intensity']);

    // Return the data as JSON
    echo json_encode([
        'sensors' => $sensorsData,
        'fan' => $fanData,
        'light' => $lightData
    ]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = "Database Logs";
include 'head.php';
?>

<body>

    <?php include 'navbar.php'; ?>

    <div class="clearfix"></div>

    <div class="container" style="margin-top: 100px;">
        <h2>Sensors Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th data-column="DateTime" data-order="asc" onclick="sortSensor(this)">DateTime</th>
                    <th data-column="Luminosity" data-order="asc" onclick="sortSensor(this)">Luminosity</th>
                    <th data-column="Temperature" data-order="asc" onclick="sortSensor(this)">Temperature</th>
                    <th data-column="Presence" data-order="asc" onclick="sortSensor(this)">Presence</th>
                </tr>
            </thead>
            <tbody id="sensorsTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        <nav>
            <ul class="pagination" id="sensorsPagination">
                <!-- Pagination links will be populated by JavaScript -->
            </ul>
        </nav>

        <h2>Fan Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th data-column="DateTime" data-order="asc" onclick="sortFan(this)">DateTime</th>
                    <th data-column="Fan_Speed" data-order="asc" onclick="sortFan(this)">Fan Speed</th>
                </tr>
            </thead>
            <tbody id="fanTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        <nav>
            <ul class="pagination" id="fanPagination">
                <!-- Pagination links will be populated by JavaScript -->
            </ul>
        </nav>

        <h2>Light Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th data-column="DateTime" data-order="asc" onclick="sortLight(this)">DateTime</th>
                    <th data-column="Intensity" data-order="asc" onclick="sortLight(this)">Intensity</th>
                </tr>
            </thead>
            <tbody id="lightTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        <nav>
            <ul class="pagination" id="lightPagination">
                <!-- Pagination links will be populated by JavaScript -->
            </ul>
        </nav>
    </div>

    <script>
        // JavaScript to handle pagination
        const rowsPerPage = 5;

        let sensorsData = []; // Store the fetched sensors data globally
        let fanData = []; // Store the fetched fan data globally
        let lightData = []; // Store the fetched light data globally

        // Fetch all data from the server
        fetch('log.php?fetchData=true')
            .then(response => response.json())
            .then(data => {
                sensorsData = data.sensors; // Store sensors data globally
                fanData = data.fan; // Store fan data globally
                lightData = data.light; // Store light data globally

                setupPagination(sensorsData, 'sensorsTableBody', 'sensorsPagination');
                setupPagination(fanData, 'fanTableBody', 'fanPagination');
                setupPagination(lightData, 'lightTableBody', 'lightPagination');
            })
            .catch(error => console.error('Error fetching data:', error));

        // Function to set up pagination
        function setupPagination(data, tableBodyId, paginationId) {
            const tableBody = document.getElementById(tableBodyId);
            const pagination = document.getElementById(paginationId);

            const totalPages = Math.ceil(data.length / rowsPerPage);

            // Function to render a specific page
            function renderPage(page) {
                // Clear the table body
                tableBody.innerHTML = '';

                // Calculate start and end indices
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                // Populate the table with rows for the current page
                const rows = data.slice(start, end);
                rows.forEach(row => {
                    const tr = document.createElement('tr');
                    for (const key in row) {
                        const td = document.createElement('td');
                        td.textContent = row[key];
                        tr.appendChild(td);
                    }
                    tableBody.appendChild(tr);
                });
            }

            // Render the first page initially
            renderPage(1);

            // Clear existing pagination links
            pagination.innerHTML = '';

            // Create pagination links
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = 'page-item';
                if (i === 1) li.classList.add('active');

                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;

                a.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Remove active class from all links
                    const links = pagination.querySelectorAll('.page-item');
                    links.forEach(link => link.classList.remove('active'));

                    // Add active class to the clicked link
                    li.classList.add('active');

                    // Render the selected page
                    renderPage(i);
                });

                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        // Function to sort the sensors table
        function sortSensor(header) {
            const column = header.getAttribute('data-column'); // Get the column to sort by
            const order = header.getAttribute('data-order'); // Get the current sorting order

            // Sort the data
            sensorsData.sort((a, b) => {
                if (order === 'asc') {
                    return a[column] > b[column] ? 1 : -1;
                } else {
                    return a[column] < b[column] ? 1 : -1;
                }
            });

            // Toggle the sorting order
            header.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');

            // Re-render the table with the sorted data
            setupPagination(sensorsData, 'sensorsTableBody', 'sensorsPagination');
        }

        // Function to sort the fan table
        function sortFan(header) {
            const column = header.getAttribute('data-column'); // Get the column to sort by
            const order = header.getAttribute('data-order'); // Get the current sorting order

            // Sort the data
            fanData.sort((a, b) => {
                if (order === 'asc') {
                    return a[column] > b[column] ? 1 : -1;
                } else {
                    return a[column] < b[column] ? 1 : -1;
                }
            });

            // Toggle the sorting order
            header.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');

            // Re-render the table with the sorted data
            setupPagination(fanData, 'fanTableBody', 'fanPagination');
        }

        // Function to sort the light table
        function sortLight(header) {
            const column = header.getAttribute('data-column'); // Get the column to sort by
            const order = header.getAttribute('data-order'); // Get the current sorting order

            // Sort the data
            lightData.sort((a, b) => {
                if (order === 'asc') {
                    return a[column] > b[column] ? 1 : -1;
                } else {
                    return a[column] < b[column] ? 1 : -1;
                }
            });

            // Toggle the sorting order
            header.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');

            // Re-render the table with the sorted data
            setupPagination(lightData, 'lightTableBody', 'lightPagination');
        }
    </script>

</body>

</html>