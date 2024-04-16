<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    /* Center the table */
    #reportTable {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    /* Adjust table styles */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color:black;
    }

    tr:hover {
      background-color: #f5f5f5;
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
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>


  <div class="h-full">
  <center><h1 class="text-green-400 text-xl">SITIN HISTORY</h1></center>
    <div id="reportTable">
    
      <table>
        <thead class="">
          <tr class="text-xs font-medium text-left text-white bg-gray-700 uppercase">
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Time In</th>
            <th class="px-4 py-2">Time Out</th>
            <th class="px-4 py-2">Lab</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $db = new SQLite3('sitin.db');

          session_start();
          if (!isset($_SESSION['id_number'])) {

            header("Location: login.php");
            exit;
          }

          $current_user_id = $_SESSION['id_number'];


          $query = $db->prepare("SELECT time_in, time_out, lab FROM sitin_student WHERE id_number = :user_id ORDER BY time_in DESC");
          $query->bindValue(':user_id', $current_user_id, SQLITE3_TEXT);
          $result = $query->execute();

          while ($row = $result->fetchArray()) {

            $formatted_date = date('F j, Y', strtotime($row['time_in']));
            $formatted_time_in = date('h:i A', strtotime($row['time_in']));
            $formatted_time_out = date('h:i A', strtotime($row['time_out']));


            echo "<tr class='border-b border-gray-700 text-sm text-gray-400 hover:bg-gray-800 hover:text-white'>";
            echo "<td class='px-4 py-2'>$formatted_date</td>";
            echo "<td class='px-4 py-2'>$formatted_time_in</td>";
            echo "<td class='px-4 py-2'>$formatted_time_out</td>";
            echo "<td class='px-4 py-2'>" . $row['lab'] . "</td>";
            echo "</tr>";
          }
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
