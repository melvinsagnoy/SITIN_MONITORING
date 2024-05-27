<?php
// Connect to the database or include your database connection file
$db = new SQLite3('sitin.db');

// Initialize variables for error/success messages
$success_message = '';
$error_message = '';

// Function to determine the color based on status
function getStatusColor($status)
{
    switch ($status) {
        case 'Pending':
            return 'text-blue-500';
            break;
        case 'Accepted':
            return 'text-green-500';
            break;
        case 'Rejected':
            return 'text-red-500';
            break;
        default:
            return ''; // No color for other statuses
            break;
    }
}

$query_purpose = $db->query("SELECT DISTINCT purpose FROM sitin_student");
$purposes = [];
while ($row = $query_purpose->fetchArray(SQLITE3_ASSOC)) {
    $purposes[] = $row['purpose'];
}

// Fetch labs from the database
$query_lab = $db->query("SELECT DISTINCT lab FROM sitin_student");
$labs = [];
while ($row = $query_lab->fetchArray(SQLITE3_ASSOC)) {
    $labs[] = $row['lab'];
}
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    // Retrieve form data
    $id_number = $_POST['id_number'] ?? '';
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$purpose = $_POST['purpose'] ?? '';
$lab = $_POST['lab'] ?? '';
$reservation_date = $_POST['reservation_date'] ?? '';
$computer = $_POST['computer'] ?? '';

// Validate form data (add more validation if needed)
if (empty($id_number) || empty($first_name) || empty($last_name) || empty($purpose) || empty($lab) || empty($reservation_date) || empty($computer)) {
    $error_message = 'Please fill in all the fields.';
} else {
    // Insert reservation into the database
    $query = $db->prepare("
        INSERT INTO reservations (id_number, first_name, last_name, purpose, lab, reservation_date, computer)
        VALUES (:id_number, :first_name, :last_name, :purpose, :lab, :reservation_date, :computer)
    ");
    $query->bindValue(':id_number', $id_number, SQLITE3_TEXT);
    $query->bindValue(':first_name', $first_name, SQLITE3_TEXT);
    $query->bindValue(':last_name', $last_name, SQLITE3_TEXT);
    $query->bindValue(':purpose', $purpose, SQLITE3_TEXT);
    $query->bindValue(':lab', $lab, SQLITE3_TEXT);
    $query->bindValue(':reservation_date', $reservation_date, SQLITE3_TEXT);
    $query->bindValue(':computer', $computer, SQLITE3_INTEGER);

    // Execute the query
    $result = $query->execute();

    if ($result) {
        $success_message = 'Reservation successfully scheduled.';
    } else {
        $error_message = 'Failed to schedule reservation. Please try again.';
    }
}
}

// Fetch reservation information for the student
$query_reservations = $db->prepare("
    SELECT * FROM reservations
    WHERE id_number = :id_number
");
$query_reservations->bindValue(':id_number', $_POST['id_number'] ?? '', SQLITE3_TEXT);
$result_reservations = $query_reservations->execute();

$reservation_data = [];
while ($row = $result_reservations->fetchArray(SQLITE3_ASSOC)) {
    $reservation_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Reservation Information</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #e2e8f0;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f8fafc;
        }

        /* Sidebar styles */
        #sidebar {
            transition: width 0.3s ease-in-out;
        }

        /* Fade in/out animation for sidebar content */
        .sidebar-content {
            animation: fade 0.3s ease-in-out;
        }

        @keyframes fade {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.1);
            }
        }

        .icon-zoom {
            animation: zoomIn 0.3s forwards;
        }

        .icon-container:hover .icon-zoom {
            animation: zoomIn 0.3s forwards;
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-900 font-mono text-white">
    <div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
        <div class="flex items-center justify-between px-4 mb-6 ">
            <div class="flex items-center">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
            </div>
            <div>
                <button id="close-menu" class="focus:outline-none">
                    <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
        <ul class="mt-6 bg-gray-600">
            <li>
                <a href="profile.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-user"></i> View Profile
                </a>
            </li>
            <li>
                <a href="view_remaining.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-clock"></i> View Remaining Session
                </a>
            </li>
            <li>
                <a href="history.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
                    <i class="fas fa-history"></i> Sitin Login History
                </a>
            </li>
            <li>
                <a href="feedback.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-comments"></i> Feedback and Reporting
                </a>
            </li>
            <li>
                <a href="safety.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-bell"></i> Safety Monitoring/Alert
                </a>
            </li>
            <li>
                <a href="view_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fa fa-bullhorn"></i> View Announcement
                </a>
            </li>
            <li>
                <a href="reservation.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-desktop"></i> Future Reservation
                </a>
            </li>

            <br>
            <li>
                <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
            </li>

        </ul>
    </div>
    <br><br><br><br>
    <div class="flex-1 px-8 py-6">
        <button id="menu-toggle" class="focus:outline-none">
            <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="ml-0 lg:ml-280 p-5">
            <h1 class="text-3xl font-bold mb-5">Student Reservation Information</h1>

            <!-- Reservation form -->
            <form method="POST" action="">
                <label for="id_number" class="block text-sm font-medium">ID Number:</label><br>
                <input type="text" id="id_number" name="id_number" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required><br><br>

                <label for="first_name" class="block text-sm font-medium">First Name:</label><br>
<input type="text" id="first_name" name="first_name" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required><br><br>

<label for="last_name" class="block text-sm font-medium">Last Name:</label><br>
<input type="text" id="last_name" name="last_name" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required><br><br>

                <label for="purpose" class="block text-sm font-medium">Purpose:</label><br>
                <select id="purpose" name="purpose" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required>
                    <option value="">Select Purpose</option>
                    <?php foreach ($purposes as $purpose_option) : ?>
                        <option value="<?php echo $purpose_option; ?>"><?php echo $purpose_option; ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label for="lab" class="block text-sm font-medium">Lab:</label><br>
                <select id="lab" name="lab" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required>
                    <option value="">Select Lab</option>
                    <?php foreach ($labs as $lab_option) : ?>
                        <option value="<?php echo $lab_option; ?>"><?php echo $lab_option; ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label for="reservation_date" class="block text-sm font-medium">Reservation Date:</label><br>
                <input type="date" id="reservation_date" name="reservation_date" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required><br><br>

                <label for="computer" class="block text-sm font-medium">Computer:</label><br>
                <select id="computer" name="computer" class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm text-black" required>
                    <option value="">Select Computer</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                </select><br><br>

                <button type="submit" name="submit_reservation" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Submit Reservation</button>
            </form>

            <!-- Reservation information -->
            <h2 class="text-2xl font-bold mb-3">Reservation Information</h2>
            <?php if (!empty($reservation_data)) : ?>
                <table class="table-auto">
                    <thead class="text-black">
                        <tr>
                            <th class="px-4 py-2">Reservation ID</th>
                            <th class="px-4 py-2">ID Number</th>
                            <th class="px-4 py-2">First Name</th>
                            <th class="px-4 py-2">Last Name</th>
                            <th class="px-4 py-2">Purpose</th>
                            <th class="px-4 py-2">Lab</th>
                            <th class="px-4 py-2">Reservation Date</th>
                            <th class="px-4 py-2">Computer</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservation_data as $reservation) : ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo $reservation['id']; ?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['id_number']; ?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['first_name'];?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['last_name'];?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['purpose']; ?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['lab']; ?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['reservation_date']; ?></td>
                                <td class="border px-4 py-2"><?php echo $reservation['computer']; ?></td>
                                <td class="border px-4 py-2 <?php echo getStatusColor($reservation['status']); ?>"><?php echo $reservation['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No reservation information available.</p>
            <?php endif; ?>
        </div>
        <!-- End Main Content -->

        <!-- JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-0');
            });

            const menuToggle = document.getElementById('menu-toggle');
            const closeMenuButton = document.getElementById('close-menu');
            const sidebar = document.getElementById('sidebar');

            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('w-64');
                if (sidebar.classList.contains('w-64')) {
                    sidebar.classList.remove('w-0');
                } else {
                    sidebar.classList.add('w-0');
                }
            });

            closeMenuButton.addEventListener('click', () => {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-0');
            });
        </script>
</body>

</html>
