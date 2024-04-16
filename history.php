<?php
$success_message = '';
$error_message = '';

$db = new SQLite3('sitin.db');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_reports'])) {
  $start_date = $_POST['start_date'] ?? '';
  $end_date = $_POST['end_date'] ?? '';

  
  $query = $db->prepare("
    SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
    FROM sitin_student s
    JOIN student st ON s.id_number = st.id_number
    WHERE s.time_in BETWEEN :start_date AND :end_date
  ");
  $query->bindValue(':start_date', $start_date, SQLITE3_TEXT);
  $query->bindValue(':end_date', $end_date, SQLITE3_TEXT);

  $result = $query->execute();
}


else {
  $query = $db->prepare("
    SELECT s.id_number, st.firstname, st.lastname, s.purpose, s.lab, s.time_in, s.time_out, s.status
    FROM sitin_student s
    JOIN student st ON s.id_number = st.id_number
  ");
  $result = $query->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  
  $student_id = $_POST['student_id'] ?? '';

  $query = $db->prepare("
      UPDATE sitin_student 
      SET time_out = CURRENT_TIMESTAMP, status = 'INACTIVE' 
      WHERE id_number = :student_id
  ");
  $query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
  $query->execute();

  $success_message = 'Student logged out successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
          <i class = "fas fa-user"></i> View Profile
        </a>
      </li>
      <li>
        <a href="view_remaining.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class = "fas fa-clock"></i> View Remaining Session
        </a>
      </li>
      <li>
        <a href="history.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
         <i class = "fas fa-history"></i> Sitin Login History
        </a>
      </li>
      <br>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class= "fas fa-sign-out-alt"></i> Log Out
        </a>
      </li>
    </ul>
  </div>

  <div class="flex-1 px-8 py-6">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>

  </div>

  <div class="mt-8">
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
  </script>

</body>

</html>
