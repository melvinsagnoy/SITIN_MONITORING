<?php
$db = new SQLite3('sitin.db');

// Check if the form is submitted for posting announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
    // Assuming you have a database connection

    // Retrieve form data
    $announcement_title = $_POST['announcement_title'] ?? '';
    $announcement_content = $_POST['announcement_content'] ?? '';

    // Insert announcement into the database
    $query = $db->prepare("
        INSERT INTO announcements (title, content, created_at) 
        VALUES (:title, :content, CURRENT_TIMESTAMP)
    ");
    $query->bindValue(':title', $announcement_title, SQLITE3_TEXT);
    $query->bindValue(':content', $announcement_content, SQLITE3_TEXT);
    $query->execute();

    // Redirect after posting the announcement
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Check if the form is submitted for deleting announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_announcement'])) {
    // Retrieve announcement ID to delete
    $announcement_id = $_POST['announcement_id'] ?? '';

    // Delete announcement from the database
    $query = $db->prepare("DELETE FROM announcements WHERE id = :id");
    $query->bindValue(':id', $announcement_id, SQLITE3_INTEGER);
    $query->execute();

    // Redirect after deleting the announcement
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

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
    <title>Post Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body class="flex min-h-screen bg-gray-900 font-mono text-white">
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-700 shadow pt-5 h-screen overflow-auto ">
    <div class="flex items-center justify-between px-4 mb-6">
    <a href="admin_dashboard.php">
        <img src="img/logo.png" alt="Logo" class="h-20 mr-4" />
    </a>
      <button id="close-menu" class="focus:outline-none">
        <svg class="h-6 w-6 hover:text-white-200 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
      </button>
    </div>
    <ul class="space-y-2 px-4">
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
      <li>
        <a href="post_a.php" class="text-gray-200 hover:text-white hover:bg-gray-400 font-medium px-4 py-2 rounded-md block">
          <i class = "fas fa-file"></i> Post Announcement
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


  <div class="flex-1 px-8 py-6 ">
    <button id="menu-toggle" class="focus:outline-none">
      <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
      </svg>
    </button>
    <div class="flex-1 px-8 py-6">
    
        
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4">Post Announcement</h1>

            <form method="POST" action="">
    <label for="announcement_title" class="block text-gray-400">Title:</label>
    <input type="text" id="announcement_title" name="announcement_title" class="mt-1 p-2 w-full border rounded-md bg-gray-800 text-gray-300">

    <label for="announcement_content" class="block text-gray-400 mt-4">Content:</label>
    <textarea id="announcement_content" name="announcement_content" rows="4" cols="50" class="mt-1 p-2 w-full border rounded-md bg-gray-800 text-gray-300"></textarea>

    <!-- Add a hidden input field to indicate the form submission for posting announcements -->
    <input type="hidden" name="post_announcement" value="1">

    <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md">Post Announcement</button>
</form>
        </div>
        <?php if (!empty($announcements)): ?>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($announcements as $announcement): ?>
            <div class="relative bg-gray-800 border border-gray-700 rounded-lg overflow-hidden shadow-lg">
                <div class="px-6 py-4">
                    <h3 class="text-xl font-semibold mb-2"><?php echo $announcement['title']; ?></h3>
                    <p class="text-gray-400 text-sm"><?php echo $announcement['created_at']; ?></p>
                    <p class="text-gray-300 mt-2"><?php echo $announcement['content']; ?></p>
                    <!-- Delete Button -->
                    <form method="POST" action="">
                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                        <button type="submit" name="delete_announcement" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 focus:outline-none">
                            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M15.293 4.293a1 1 0 0 1 1.414 1.414l-1.414 1.414 1.414 1.414a1 1 0 1 1-1.414 1.414l-1.414-1.414-1.414 1.414a1 1 0 1 1-1.414-1.414l1.414-1.414-1.414-1.414a1 1 0 1 1 1.414-1.414l1.414 1.414zM10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No announcements available.</p>
<?php endif; ?>
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
