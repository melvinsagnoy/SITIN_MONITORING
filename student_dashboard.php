<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="flex min-h-screen bg-gray-500 font-mono " >

  <div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
    <div class="flex items-center justify-between px-4 mb-6 ">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="img/logo.png" alt="Logo" class="h-20 mr-4" />  
      <div>
        <button id="close-menu" class="focus:outline-none">
          <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </button>
      </div>
    </div>
    <ul class="mt-6 bg-gray-600">
      <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
        <a href="profile.php">View Profile</a>
      </li>
      <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
        <a href="#">SITIN</a>
      </li>
      <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
        <a href="#">Reservation</a>
      </li>
      <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
        <a href="#">Remaining Session</a>
      </li>
      <li class="px-4 py-2 rounded-md text-base font-medium text-green-400 hover:bg-gray-300 hover:text-gray-900">
        <a href="login.php">Log Out</a>
      </li>
    </ul>
  </div>
    
  <div class="flex-1 px-8 py-6">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>
    <center><h2 class="text-6xl font-semibold mb-6 text-green-400">Student Dashboard</h2></center>

    <center><br><br><br><br><br><br><img class = "flex " src = "img/dashboard.png"></center>

    
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
