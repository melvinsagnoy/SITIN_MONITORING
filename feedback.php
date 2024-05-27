<?php
// Start session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection
    $db = new SQLite3('sitin.db');

    $feedback = $_POST['feedback'] ?? '';
    
    // Get student ID from session
  

    // Insert feedback into the database along with the student ID
    $query = $db->prepare("INSERT INTO feedback (feedback_content, created_at) VALUES (:content, CURRENT_TIMESTAMP)");
    $query->bindValue(':content', $feedback, SQLITE3_TEXT);
   
    $query->execute();

    // Optionally, you can display a success message or perform other actions
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body class="flex min-h-screen bg-gray-900 text-white">

    
    <div class="fixed inset-y-0 w-0 bg-white shadow pt-5 h-screen overflow-auto transition duration-300 ease-in-out bg-gray-600 text-white slide-in-from-left" id="sidebar">
        <div class="flex items-center justify-between px-4 mb-6 ">
            <div class="flex items-center">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="student_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
    </a>
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

<body>
<div class="flex-1 px-8 py-6">
        

<div class="flex-1 px-8 py-6">


        <div class="container mx-auto">
            <h1 class="text-3xl font-bold mb-4">Submit Feedback</h1>
            <form action="" method="POST" class="max-w-md">
                <label for="feedback" class="block text-gray-300 mb-2">Feedback:</label>
                <textarea id="feedback" name="feedback" rows="4" class="bg-gray-800 text-gray-300 rounded-md w-full px-4 py-2 mb-4" placeholder="Enter your feedback here..."></textarea>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-md">Submit Feedback</button>
            </form>
            <?php
            // Check if the form has been submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if feedback is not empty
                if (!empty($_POST['feedback'])) {
                    // Display a message after feedback submission
                    echo "<p class='text-green-500 mt-4'>Feedback submitted successfully.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
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
</html>
