<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
  <style>
    /* Animation for sidebar */
    #sidebar {
      transition: width 0.3s ease-in-out;
    }

    /* Fade in/out animation for sidebar content */
    .sidebar-content {
      animation: fade 0.3s ease-in-out;
    }

    @keyframes fade {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }

    @keyframes zoomIn {
        from {
            transform: scale(1);
        }
        to {
            transform: scale(1.1);
        }
    }

    .icon-zoom {
        animation: zoomIn 0.3s forwards;
    }

    .icon-container:hover .icon-zoom {
        animation: zoomIn 0.3s forwards;
    }
  </style>
</head>

<body class="flex min-h-screen bg-gray-900 font-mono text-white">

  <div class="fixed inset-y-0 w-64 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white" id="sidebar">
    <div class="flex items-center justify-between px-4 mb-6 ">
      <div class="flex items-center">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="flex items-center">
    <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
    </a>
</div>
      </div>
      <div>
        <button id="close-menu" class="focus:outline-none">
          <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
          </svg>
        </button>
      </div>
    </div>
    <div class="sidebar-content">
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
          <a href="view_feedback.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-comments"></i> Feedbacks and Reporting
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
  </div>
  <br><br><br><br>
  <div class="flex-1 px-8 py-6">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>
    <center>
      <h2 class="text-4xl font-semibold mb-6 text-green-400">Admin Dashboard</h2>
    </center>
    <br><br><br><br>
   
<div class="mt-8">
    <div class="grid grid-cols-2 gap-4">
        <div class="p-8 bg-gray-800 rounded-md text-center icon-container">
            <a href="search.php">
                <i class="fas fa-search text-5xl text-gray-400 mb-4 icon-zoom"></i>
                <p class="text-lg font-semibold">Search</p>
                <p class="text-gray-400">Search for something.</p>
            </a>
        </div>
        <div class="p-8 bg-gray-800 rounded-md text-center icon-container">
            <a href="delete_admin.php">
                <i class="fas fa-trash text-5xl text-gray-400 mb-4 icon-zoom"></i>
                <p class="text-lg font-semibold">Delete</p>
                <p class="text-gray-400">Delete something.</p>
            </a>
        </div>
        <div class="p-8 bg-gray-800 rounded-md text-center icon-container">
            <a href="view_records.php">
                <i class="fas fa-eye text-5xl text-gray-400 mb-4 icon-zoom"></i>
                <p class="text-lg font-semibold">View Sitin Records</p>
                <p class="text-gray-400">View records.</p>
            </a>
        </div>
        <div class="p-8 bg-gray-800 rounded-md text-center icon-container">
            <a href="generate_reports.php">
                <i class="fas fa-file text-5xl text-gray-400 mb-4 icon-zoom"></i>
                <p class="text-lg font-semibold">Generate Reports</p>
                <p class="text-gray-400">Generate reports.</p>
            </a>
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
  </script>

</body>

</html>
