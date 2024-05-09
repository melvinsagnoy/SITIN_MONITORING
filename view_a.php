<?php
// Establish a database connection
$db = new SQLite3('sitin.db');

// Retrieve announcements from the database
$query = $db->query("
    SELECT * FROM announcements ORDER BY created_at DESC
");

// Check if there are announcements
if ($query) {
    $announcements = [];
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        $announcements[] = $row;
    }
} else {
    $announcements = [];
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
<div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white slide-in-from-left" id="sidebar">
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
      <li>
        <a href="feedback.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class="fas fa-comments"></i> Feedback and Reporting
        </a>
      </li>
      <li>
        <a href="safety.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class="fas fa-bell"></i> Safety Monitoring/Alert
        </a>
      </li>
      <li>
        <a href="view_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
        <i class="fa fa-bullhorn"></i> View Announcement
        </a>
      </li>
      <li>
        <a href="reservation.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class="fas fa-desktop"></i> Future Reservation
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
            <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">Announcements</h1>

        <?php if (!empty($announcements)): ?>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden shadow-lg">
                            <div class="px-6 py-4">
                                <h3 class="text-xl font-semibold mb-2"><?php echo $announcement['title']; ?></h3>
                                <p class="text-gray-400 text-sm"><?php echo $announcement['created_at']; ?></p>
                                <p class="text-gray-300 mt-2"><?php echo $announcement['content']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php else: ?>
            <p>No announcements available.</p>
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

