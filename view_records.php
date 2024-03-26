<?php
$success_message = '';
$error_message = '';

$db = new SQLite3('sitin.db');
$query = $db->prepare("
  SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
  FROM sitin_student s
  JOIN student st ON s.id_number = st.id_number
");
$result = $query->execute();


$first_view = !isset($_COOKIE['first_view']);

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

  
  setcookie('first_view', 'visited', time() + 3600 * 24 * 365); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Sitin Records</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
            .transition-upper-to-lower {
                animation: upper-to-lower 0.7s ease forwards;
            }

            @keyframes upper-to-lower {
                0% {
                    opacity: 0;
                    transform: translateY(-50%);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            table {
      font-size: 14px; 
      max-width: 75%; 
      width: 75%; 
      table-layout: fixed; 
    }

    th,
    td {
      padding: 8px; 
    }

    </style>
</head>

<body class="flex min-h-screen bg-gray-800 font-mono text-white">

  <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-700 shadow pt-5 h-screen overflow-auto ">
    <div class="flex items-center justify-between px-4 mb-6">
      <img src="img/logo.png" alt="Logo" class="h-20 mr-4">
      <button id="close-menu" class="focus:outline-none">
        <svg class="h-6 w-6 hover:text-white-200 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>
    <ul class="space-y-2 px-4">
      <li>
        <a href="search.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          Search
        </a>
      </li>
      <li>
        <a href="delete_admin.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        Delete
        </a>
        </li>
      <li>
        <a href="#" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          View Sitin Records
        </a>
      </li>
      <li>
        <a href="generate_reports.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          Generate Reports
        </a>
      </li>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          Log Out
        </a>
      </li>
    </ul>
  </div>

  <div class="flex-1 px-8 py-6 ">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>
    <center>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Sitin Records</h2>
    </center>

    <div class="mt-8 ">
      <?php if ($result && $result->numColumns() > 0): ?>
      <center>
        <table class="table-auto w-full shadow-md rounded-md overflow-x-auto transition-upper-to-lower">
          <thead>
            <tr class="text-xs font-medium text-left text-white bg-gray-700 uppercase">
              <th class="px-4 py-2">ID NUMBER</th>
              <th class="px-4 py-2">FIRST NAME</th>
              <th class="px-4 py-2">LAST NAME</th>
              <th class="px-4 py-2">PURPOSE</th>
              <th class="px-4 py-2">LAB</th>
              <th class="px-4 py-2">TIME IN</th>
              <th class="px-4 py-2">TIME OUT</th>
              <th class="px-4 py-2">STATUS</th>
              <th class="px-4 py-2">ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <tr class="border-b border-gray-700 text-sm text-gray-400 hover:bg-gray-800 hover:text-white">
              <td class="px-4 py-2"><?php echo $row['id_number']; ?></td>
              <td class="px-4 py-2 capitalize"><?php echo $row['firstname']; ?></td>
              <td class="px-4 py-2 capitalize"><?php echo $row['lastname']; ?></td>
              <td class="px-4 py-2 capitalize"><?php echo $row['purpose']; ?></td>
              <td class="px-4 py-2 capitalize"><?php echo $row['lab']; ?></td>
              <td class="px-4 py-2"><?php echo $row['time_in']; ?></td>
              <td id="time-out-column" class="px-4 py-2"><?php echo $row['time_out']; ?></td>
              <td class="px-4 py-2 text-green-400"><?php echo $row['status']; ?></td>
              <td class="px-4 py-2">
                <form method="POST" action="">
                  <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                  <button type="submit" name="logout" class="text-red-500 hover:text-red-700 px-2 py-1 rounded-md focus:outline-none">Logout</button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
      </center>
      <?php else: ?>
      <p class="text-red-500 text-center">No sitin records found.</p>
      <?php endif; ?>
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

  // Function to show/hide the "Time Out" column
  function toggleTimeOutColumn() {
    const timeOutColumn = document.getElementById('time-out-column');
    timeOutColumn.style.display = (timeOutColumn.style.display === 'none') ? 'table-cell' : 'none';
  }
</script>
</body>

</html>