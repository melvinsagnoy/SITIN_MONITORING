<?php

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

    $db = new SQLite3('sitin.db');

    $data = json_decode(file_get_contents("php://input"), true);
    $studentId = $data['studentId'];

    $query = $db->prepare("DELETE FROM student WHERE id_number = :studentId");
    $query->bindParam(':studentId', $studentId, SQLITE3_TEXT);
    $result = $query->execute();

    if ($result) {
        echo json_encode(array("success" => true, "studentId" => $studentId));
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
    <title>Delete</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    <style>
            .transition-pop-out {
            animation: pop-out 0.7s ease forwards;
        }

        @keyframes pop-out {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        </style>
</head>

<body class="bg-gray-800 font-mono text-white">


<div class="fixed inset-y-0 w-64 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
     
        <div class="flex items-center justify-between px-4 mb-6">
        <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
    </a>
            <button id="close-menu" class="focus:outline-none">
                <svg class="h-6 w-6 hover:text-white-200" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6 18L18 6M6 6L18 18"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
        <ul class="mt-6 bg-gray-600">
        <li>
          <a href="search.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block"><i class="fas fa-search"></i> Search
          </a>
        </li>
        <li>
          <a href="delete_admin.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
            <i class="fas fa-trash"></i> Delete
          </a>
        </li>
        <li>
          <a href="view_records.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
            <i class="fas fa-eye"></i> View Sitin Records
          </a>
        </li>
        <li>
          <a href="generate_reports.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
            <i class="fas fa-file"></i> Generate Reports
          </a>
        </li>
        <li>
          <a href="post_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fa fa-bullhorn"></i> Post Announcements
          </a>
        </li>
        <li>
          <a href="approval.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
            <i class="fas fa-file"></i> Booking Request and Approval
          </a>
        </li>
        <li>
    <a href="analytics.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class="fas fa-chart-pie"></i> Daily Analytics
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


    <div class="flex-1 px-8 py-6">
        <button id="menu-toggle" class="focus:outline-none">
            <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M4 6H20M4 12H20M4 18H11Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>
        <center>
            <h2 class="text-4xl font-semibold mb-6 text-green-400">Delete</h2>
        </center>

        <div class="container mx-auto px-4 sm:px-8 max-w-3xl transition-pop-out">
            <div class="py-8">
                <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-x-auto">
                    <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
                        <table class="min-w-full leading-normal bg-gray-200 text-white">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold uppercase tracking-wider">
                                        ID Number
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold uppercase tracking-wider">
                                        First Name
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold uppercase tracking-wider">
                                        Last Name
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-900 text-left text-xs font-semibold uppercase tracking-wider">
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
                                    echo "<td class='px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm'>$row[0]</td>";
                                    echo "<td class='px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm'>$row[1]</td>";
                                    echo "<td class='px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm'>$row[2]</td>";
                                    echo "<td class='px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm'>$row[3]</td>";
                                    echo "<td class='px-5 py-5 border-b border-gray-200 bg-gray-800 text-sm'><button class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded' onclick='deleteStudent(\"$row[0]\")'>Delete</button></td>";
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
  
    function deleteStudent(studentId) {
    if (confirm("Are you sure you want to delete this student?")) {
        fetch('<?php echo $_SERVER["PHP_SELF"]; ?>', { 
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

                    const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                    if (row) {
                        row.remove();
                    }
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

