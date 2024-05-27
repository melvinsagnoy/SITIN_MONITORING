<?php
// Assuming you have a database connection
$db = new SQLite3('sitin.db');

$query = $db->query("
    SELECT feedback.*
    FROM feedback
    ORDER BY feedback.created_at DESC
");

// Check if there are feedback entries
if ($query) {
    $feedback_entries = [];
    while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
        $feedback_entries[] = $row;
    }
} else {
    $feedback_entries = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white font-mono">
    <div class="flex min-h-screen">
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
        <!-- Main Content -->
        <div class="flex-1 px-8 py-6">
            <button id="menu-toggle" class="focus:outline-none">
                <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <div class="container mx-auto">
                <h1 class="text-3xl font-bold mb-4">View Feedback</h1>
                <!-- Feedback Cards -->
                <?php foreach ($feedback_entries as $entry): ?>
                    <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden shadow-lg mb-4">
                        <div class="px-6 py-4">
                        <p class="text-gray-300 mb-2"><strong>ID Number:</strong> <?php echo $entry['id_number']; ?></p>
                            <p class="text-gray-300 mb-2"><strong>Feedback:</strong> <?php echo $entry['feedback_content']; ?></p>
                            <p class="text-gray-400"><strong>Created At:</strong> <?php echo $entry['created_at']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- End of Feedback Cards -->
            </div>
        </div>
    </div>

    <!-- JavaScript for Sidebar Toggle -->
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
