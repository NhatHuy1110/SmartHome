<?php
// Include the Connection2.php file
require_once 'Connection2.php';

// Create an instance of DBConn
$db = new DBConn();

// Check if the request is for JSON data
if (isset($_GET['fetchData']) && $_GET['fetchData'] === 'true') {
    // Fetch all data from Sensors, Fan, and Light tables
    $sensorsData = $db->selectWhere('Sensors', [], 'DateTime', 0, 'DESC');
    $fanData = $db->selectWhere('Fan', [], 'DateTime', 0, 'DESC');
    $lightData = $db->selectWhere('Light', [], 'DateTime', 0, 'DESC');

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
                    <th>RID</th>
                    <th>DateTime</th>
                    <th>Luminosity</th>
                    <th>Temperature</th>
                    <th>Presence</th>
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
                    <th>RID</th>
                    <th>FID</th>
                    <th>DateTime</th>
                    <th>Fan Speed</th>
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
                    <th>RID</th>
                    <th>LID</th>
                    <th>DateTime</th>
                    <th>Intensity</th>
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

        // Fetch all data from the server
        fetch('log.php?fetchData=true')
            .then(response => response.json())
            .then(data => {
                // Populate tables with pagination
                setupPagination(data.sensors, 'sensorsTableBody', 'sensorsPagination');
                setupPagination(data.fan, 'fanTableBody', 'fanPagination');
                setupPagination(data.light, 'lightTableBody', 'lightPagination');
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
    </script>

</body>

</html>