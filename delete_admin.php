
<?php

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    $db = new SQLite3('sitin.db');
    

    $data = json_decode(file_get_contents("php://input"), true);
    $studentId = $data['studentId'];

    $query = $db->prepare("DELETE FROM student WHERE id_number = :studentId");
    $query->bindParam(':studentId', $studentId, SQLITE3_TEXT);
    $result = $query->execute();


    if ($result) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to delete student record."));
    }
    exit(); 
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

<div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white"
    id="sidebar">
    <div class="flex items-center justify-between px-4 mb-6 ">
        <div class="flex items-center">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
        </div>
        <div>
            <button id="close-menu" class="focus:outline-none">
                <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
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
        <h2 class="text-6xl font-semibold mb-6 text-green-400 font-mono">Admin Dashboard</h2>
    </center>

    <div class="container mx-auto px-4 sm:px-8 max-w-3xl">
        <div class="py-8">
            <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                                    ID Number
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                                    First Name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                                    Last Name
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                                    Email
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold text-gray-100 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            $db = new SQLite3('sitin.db');
                            $query = $db->query("SELECT id_number, firstname, lastname, email FROM student");
                            while ($row = $query->fetchArray()) {
                                echo "<tr>";
                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>$row[0]</td>";
                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>$row[1]</td>";
                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>$row[2]</td>";
                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'>$row[3]</td>";
                                echo "<td class='px-5 py-5 border-b border-gray-200 bg-white text-sm'><button class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded' onclick='deleteStudent(\"$row[0]\")'>Delete</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const closeMenuButton = document.getElementById('close-menu');
    const sidebar = document.getElementById('sidebar');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('w-64'); // Toggle sidebar width
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

    function deleteStudent(studentId) {
        if (confirm("Are you sure you want to delete this student?")) {
            fetch('<?php echo $_SERVER["PHP_SELF"]; ?>', { // Using PHP_SELF to target the same file
                    method: 'DELETE',
                    body: JSON.stringify({
                        studentId: studentId
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Student with ID ${studentId} deleted successfully.`);
                        // Optionally, you can remove the row from the table here if needed
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the student record.');
                });
        }
    }
</script>

</body>
</html>
