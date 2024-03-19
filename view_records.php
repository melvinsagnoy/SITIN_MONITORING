<?php

$success_message = '';
$error_message = '';

$result = null;

$db = new SQLite3('sitin.db');
$query = $db->prepare("
    SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
    FROM sitin_student s
    JOIN student st ON s.id_number = st.id_number
");
$result = $query->execute();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $student_id = $_POST['student_id'] ?? '';
    
  
    if ($student_id) {
        
        $current_time = date('Y-m-d H:i:s');
        $query = $db->prepare("UPDATE sitin_student SET time_out = :time_out, status = 'INACTIVE' WHERE id_number = :student_id AND status = 'ACTIVE'");
        $query->bindValue(':time_out', $current_time, SQLITE3_TEXT);
        $query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
        
        $result = $query->execute();
        
        if ($result) {
            $success_message = "Logged out successfully.";
            
            $query = $db->prepare("
                SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
                FROM sitin_student s
                JOIN student st ON s.id_number = st.id_number
            ");
            $result = $query->execute();

            
            $decrement_query = $db->prepare("UPDATE sitin_student SET remaining_sessions = remaining_sessions - 1 WHERE id_number = :student_id");
            $decrement_query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
            $decrement_result = $decrement_query->execute();
            if (!$decrement_result) {
                $error_message = "Error decrementing remaining session count.";
            }
        } else {
            $error_message = "Error logging out.";
        }
    } else {
        $error_message = "Student ID is required.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sitin Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex min-h-screen bg-gray-500 font-mono">


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
                <a href="search.php">Search</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">Delete</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">SITIN</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">View Sitin Records</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="#">Generate Reports</a>
            </li>
            <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
                <a href="login.php">Log Out</a>
            </li>
        </ul>
    </div>

  
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
        <center>
            <h2 class="text-6xl font-semibold mb-6 text-green-400">Sitin Records</h2>
        </center>

        <div class="mt-8">
            <?php if ($result->numColumns() > 0): ?>
            <center>
                <table class="table-auto">
                    <thead>
                        <tr class="text-white">
                            <th class="px-4 py-2">ID NUMBER</th>
                            <th class="px-4 py-2">FIRST NAME</th>
                            <th class="px-4 py-2">LAST NAME</th>
                            <th class="px-4 py-2">PURPOSE</th>
                            <th class="px-4 py-2">LAB</th>
                            <th class="px-4 py-2">TIME IN</th>
                            <th class="px-4 py-2">TIME OUT</th>
                            <th class="px-4 py-2 ">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                        <tr class="text-white">
                            <td class="border px-4 py-2"><?php echo $row['id_number']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['firstname']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['lastname']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['purpose']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['lab']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['time_in']; ?></td>
                            <td class="border px-4 py-2"><?php echo $row['time_out']; ?></td>
                            <td class="border px-4 py-2 text-green-400"><?php echo $row['status']; ?></td>
                            <td class="border px-4 py-2">
                                <form method="POST" action="">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                                    <button type="submit" name="logout" class="text-red-100 hover:text-red-400">Logout</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </center>
            <?php else: ?>
            <p class="text-red-500">No sitin records found.</p>
            <?php endif; ?>
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
