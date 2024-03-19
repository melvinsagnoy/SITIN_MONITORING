<?php
// PHP script to handle form submission
$remaining_sessions = 30; // Default value
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sitin'])) {
    $db = new SQLite3('sitin.db');
    
    $student_id = $_POST['student_id'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $lab_option = $_POST['lab_option'] ?? '';
    
    // Check if all required fields are filled out
    if ($student_id && $purpose && $lab_option) {
        // MAG INSErT SITIN record with time-in and status ACTIVE
        $current_time = date('Y-m-d H:i:s');
        $query = $db->prepare("INSERT INTO sitin_student (id_number, firstname, lastname, purpose, lab, time_in, status, remaining_sessions) VALUES (:id_number, :firstname, :lastname, :purpose, :lab, :time_in, 'ACTIVE', :remaining_sessions)");
        $query->bindValue(':id_number', $student_id, SQLITE3_TEXT);
        $query->bindValue(':firstname', $_POST['firstname'], SQLITE3_TEXT);
        $query->bindValue(':lastname', $_POST['lastname'], SQLITE3_TEXT);
        $query->bindValue(':purpose', $purpose, SQLITE3_TEXT);
        $query->bindValue(':lab', $lab_option, SQLITE3_TEXT);
        $query->bindValue(':time_in', $current_time, SQLITE3_TEXT);
        $query->bindValue(':remaining_sessions', $remaining_sessions, SQLITE3_INTEGER);
        
        $result = $query->execute();
        
        if ($result) {
            $success_message = "SITIN record successfully stored.";
            // No need to decrement remaining session count here
        } else {
            $error_message = "Error storing SITIN record.";
        }
    } else {
        $error_message = "Please fill out all required fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex min-h-screen bg-gray-500 font-mono ">

    <!-- Sidebar -->
    <div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white"
        id="sidebar">
        <div class="flex items-center justify-between px-4 mb-6 ">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
            <div>
                <button id="close-menu" class="focus:outline-none">
                    <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6 18L18 6M6 6L18 18"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
        <ul class="mt-6 bg-gray-600">
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">Search</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">Delete</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="view_records.php">View Sitin Records</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">Generate Reports</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="login.php">Log Out</a>
            </li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="flex-1 px-8 py-6">
        <button id="menu-toggle" class="focus:outline-none">
            <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M4 6H20M4 12H20M4 18H11Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
        <center><br><br>
            <h2 class="text-6xl font-semibold mb-6 text-green-400">Admin Dashboard</h2><br><br><br><br><br><br><br><br><br><br>
            <form method="GET" action="">
                <input type="text" name="search_id" placeholder="Enter ID number" style="width: 500px; height: 50px; border-radius: 10px;">
                <button type="submit" class ="text-green-400 rounded-full text-2xl">Search</button>
            </form>
        </center>

        <div class="mt-8">
            <?php
            $db = new SQLite3('sitin.db');

            if (isset($_GET['search_id'])) {
                $search_id = $_GET['search_id'];

                $query = $db->prepare("
                    SELECT id_number, firstname, lastname, email 
                    FROM student 
                    WHERE id_number = :id"
                );
                $query->bindParam(':id', $search_id);
                $result = $query->execute();

                if ($row = $result->fetchArray()) {
                    $remaining_sessions = 30; // Default value
                    $session_query = $db->prepare("SELECT remaining_sessions FROM sitin_student WHERE id_number = :student_id");
                    $session_query->bindValue(':student_id', $row['id_number'], SQLITE3_TEXT);
                    $session_result = $session_query->execute();
                    $session_row = $session_result->fetchArray();
                    if ($session_row) {
                        $remaining_sessions = $session_row['remaining_sessions'];
                    }
                    ?>
                    <center>
                        <table class="table-auto">
                            <h3 class="text-green-500">RECORD FOUND</h3>
                            <thead>
                                <tr class="text-white">
                                    <th class="px-4 py-2">ID NUMBER</th>
                                    <th class="px-4 py-2">FIRST NAME</th>
                                    <th class="px-4 py-2">LAST NAME</th>
                                    <th class="px-4 py-2">EMAIL</th>
                                    <th class="px-4 py-2">PURPOSE</th>
                                    <th class="px-4 py-2">LABORATORY ROOM</th>
                                    <th class="px-4 py-2">REMAINING SESSIONS (<?php echo $remaining_sessions; ?>)</th>
                                    <th class="px-4 py-2">SITIN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-white">
                                    <td class="border px-4 py-2"><?php echo $row['id_number']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $row['firstname']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $row['lastname']; ?></td>
                                    <td class="border px-4 py-2"><?php echo $row['email']; ?></td>
                                    <td class="border px-4 py-2">
                                        <form method="POST" action="">
                                            <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                                            <input type="hidden" name="firstname" value="<?php echo $row['firstname']; ?>">
                                            <input type="hidden" name="lastname" value="<?php echo $row['lastname']; ?>">
                                            <input  class = "text-black"type="text" name="purpose" placeholder="Purpose" required>
                                    </td>
                                    <td class="border px-4 py-2 text-black">
                                        <select name="lab_option" required>
                                            <option value="">Select Lab Option</option>
                                            <option value="lab 524">Lab 524</option>
                                            <option value="lab 525">Lab 525</option>
                                            <option value="lab 526">Lab 526</option>
                                            <option value="lab 528">Lab 528</option>
                                            <option value="lab 542">Lab 542</option>
                                        </select>
                                    </td>
                                    <td class="border px-4 py-2 text-black">
                                   <center> <input type="text" name="default_session" value="<?php echo $remaining_sessions; ?>" readonly></center>
                                    </td>
                                    <td class="border px-4 py-2 bg-green-400">
                                        <button type="submit" name="submit_sitin">SITIN</button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </center>
                    <?php
                } else {
                    echo "<center><p class='text-red-500'>No student found with the provided ID.</p></center> ";
                    
                    echo "<script>alert('No student found with the provided ID.');</script>";
                }
            }
            ?>
            <script>
                // JavaScript to show success message prompt
                <?php if ($success_message): ?>
                alert('<?php echo $success_message; ?>');
                <?php endif; ?>
            </script>
        </div>
    </div>

    <script>
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
