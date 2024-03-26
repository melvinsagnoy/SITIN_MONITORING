<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
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
          View Profile
        </a>
      </li>
      <li>
        <a href="view_remaining.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          View Remaining Session
        </a>
      </li>
      <li>
        <a href="#" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          SITIN
        </a>
      </li>
      <li>
        <a href="#" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          Make Reservation
        </a>
      </li>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          Log Out
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
        <thead class="bg-gray-800">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
              ID Number
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
              Remaining Session
            </th>
          </tr>
        </thead>
        <tbody class="bg-gray-900 divide-y divide-gray-200">
          <?php
     
          $database = new SQLite3('sitin.db');

     
          $sql = "SELECT id_number, remaining_sessions FROM sitin_student"; 
          $result = $database->query($sql);

         
          if ($result) {
              // Output data of each row
              while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                  echo "<tr class='text-gray-300'>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["id_number"] . "</td>";
                  echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["remaining_sessions"] . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='2' class='px-6 py-4 whitespace-nowrap text-center'>No results found</td></tr>";
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
