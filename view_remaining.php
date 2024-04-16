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
  <div class="fixed inset-y-0 w-64 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
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
    <center>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Student Dashboard</h2>
    </center>

    <div class="mt-8">
      <table class="min-w-full divide-y divide-gray-200">
 
        <tbody class="bg-gray-900 divide-y divide-gray-200">
        <?php
          session_start(); // Start session

          // Check if user is logged in
          if (!isset($_SESSION['id_number'])) {
              // Redirect to login page if not logged in
              header("Location: login.php");
              exit();
          }

          // Get the currently logged-in user's ID number from the session
          $current_id_number = $_SESSION['id_number'];

          $database = new SQLite3('sitin.db');

          // Use a prepared statement to prevent SQL injection
          $sql = "SELECT id_number, remaining_sessions FROM sitin_student WHERE id_number = :id_number";
          $stmt = $database->prepare($sql);
          $stmt->bindValue(':id_number', $current_id_number, SQLITE3_TEXT);
          $result = $stmt->execute();

          if ($result) {
              // Output data of the logged-in student
              $row = $result->fetchArray(SQLITE3_ASSOC);
              echo "<div class='max-w-sm mx-auto bg-gray-800 rounded overflow-hidden shadow-lg'>";
              echo "<div class='px-6 py-4'>";
              echo "<div class='font-bold text-xl mb-2 text-white'>Student Information</div>";
              echo "<p class='text-gray-300'><strong>ID Number:</strong> " . $row["id_number"] . "</p>";
              echo "<p class='text-gray-300'><strong>Remaining Sessions:</strong> " . $row["remaining_sessions"] . "</p>";
              echo "</div>";
              echo "</div>";
          } else {
              echo "<p class='text-center text-gray-300'>No results found</p>";
          }

          // Close connection
          $database->close();
          ?>


        </tbody>
      </table>
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
  </script>

</body>

</html>