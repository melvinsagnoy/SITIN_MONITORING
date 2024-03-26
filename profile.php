<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    <style>
        @keyframes slideInFromLeft {
            0% {
                opacity: 0;
                transform: translateX(-100%);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-from-left {
            animation: slideInFromLeft 0.5s ease-out;
        }

        .transition-colors {
            transition-property: color;
        }

        .transition-opacity {
            transition-property: opacity;
        }

        .transition-all {
            transition-property: all;
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-900 text-white">

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
            <svg class="h-6 w-6 text-white hover:text-gray-900" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </button>
        <center>
            <h2 class="text-6xl font-semibold mb-6 text-green-400">Student Profile</h2>
        </center>

        <!-- Profile Form -->
        <div class="max-w-xl mx-auto bg-gray-700 shadow-md rounded px-8 pt-6 pb-8 mb-4 slide-in-from-left">
            <form method="POST" action="" class="mt-8">
                <?php
                // Connect to the SQLite database
                $db = new SQLite3('sitin.db');

                // Check if form is submitted
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Retrieve form data
                    $id_number = $_POST['id_number'];
                    $firstname = $_POST['firstname'];
                    $lastname = $_POST['lastname'];
                    $email = $_POST['email'];

                    // Prepare update statement
                    $stmt = $db->prepare("UPDATE student SET firstname = :firstname, lastname = :lastname, email = :email WHERE id_number = :id_number");
                    $stmt->bindValue(':id_number', $id_number, SQLITE3_TEXT);
                    $stmt->bindValue(':firstname', $firstname, SQLITE3_TEXT);
                    $stmt->bindValue(':lastname', $lastname, SQLITE3_TEXT);
                    $stmt->bindValue(':email', $email, SQLITE3_TEXT);

                    // Execute the statement
                    if ($stmt->execute()) {
                        echo "<p class='text-green-500'>Profile updated successfully.</p>";
                    } else {
                        echo "<p class='text-red-500'>Error updating profile.</p>";
                    }
                }

                // Fetch student data from the database
                $query = $db->query('SELECT id_number, firstname, lastname, email FROM student');
                $student = $query->fetchArray(SQLITE3_ASSOC);

                if ($student) {
                    ?>
                    <input type="hidden" name="id_number" value="<?= $student['id_number'] ?>">
                    <div class="mb-4 c\l">
                        <label class="block text-sm font-bold mb-2 text-white">First Name:</label>
                        <input type="text" name="firstname" value="<?= $student['firstname'] ?>" class="border border-gray-400 p-2 w-full bg-gray-600 text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-white text-sm font-bold mb-2">Last Name:</label>
                        <input type="text" name="lastname" value="<?= $student['lastname'] ?>" class="border border-gray-400 p-2 w-full bg-gray-600 text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-white text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" value="<?= $student['email'] ?>" class="border border-gray-400 p-2 w-full bg-gray-600 text-white">
                    </div>
                    <button type="submit" class="bg-gray-700 hover:bg-gray-500 text-green-400 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Profile</button>
                <?php
                } else {
                    echo "<p>No student data found.</p>";
                }

                // Close the database connection
                $db->close();
                ?>
            </form>
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
