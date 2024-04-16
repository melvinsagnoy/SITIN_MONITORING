<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
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
        <a href="search.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block"><i class="fas fa-search"></i> Search
        </a>
      </li>
      <li>
        <a href="delete_admin.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class = "fas fa-trash"></i>  Delete
        </a>
      </li>
      <li>
        <a href="view_records.php" class="text-gray-200 hover:text-white font-medium hover:bg-gray-400 px-4 py-2 rounded-md block active">
          <i class = "fas fa-eye"></i> View Sitin Records
        </a>
      </li>
      <li>
        <a href="generate_reports.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class = "fas fa-file"></i> Generate Reports
        </a>
      </li>
      <br>
      <li>
        <a href="login.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class = "fas fa-sign-out-alt"></i> Log Out
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
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Admin Dashboard</h2>
    </center>

    <div class="mt-8">
    <center><br><br><br><br><br><br><img class = "flex " src = "img/dashboard.png"></center>
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
  </script>

</body>

</html>
