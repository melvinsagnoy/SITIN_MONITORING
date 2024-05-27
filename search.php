<?php

$remaining_sessions = 30; 
$success_message = '';
$error_message = '';

try {
    $db = new SQLite3('sitin.db');
} catch (Exception $e) {
    error_log("SQLite3 connection failed: ". $e->getMessage());
    die("Database connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_sitin'])) {
    $db = new SQLite3('sitin.db');
    
    $student_id = $_POST['student_id'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $lab_option = $_POST['lab_option'] ?? '';

    if ($student_id && $purpose && $lab_option) {

        $existing_sitin_query = $db->prepare("SELECT status FROM sitin_student WHERE id_number = :student_id AND status = 'ACTIVE'");
        $existing_sitin_query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
        $existing_sitin_result = $existing_sitin_query->execute();
        $existing_sitin_row = $existing_sitin_result->fetchArray();

        if ($existing_sitin_row) {

            echo "<script>alert('You already have an active sit-in. You cannot sit in again :( :(');</script>";
        } else {
            
            $current_time = date('Y-m-d H:i:s');
            $query = $db->prepare("INSERT INTO sitin_student (id_number, firstname, lastname, purpose, lab, time_in, status, remaining_sessions) VALUES (:id_number, :firstname, :lastname, :purpose, :lab, :time_in, 'ACTIVE', :remaining_sessions)");
            $query->bindValue(':id_number', $student_id, SQLITE3_TEXT);
            $query->bindValue(':firstname', $_POST['firstname'], SQLITE3_TEXT);
            $query->bindValue(':lastname', $_POST['lastname'], SQLITE3_TEXT);
            $query->bindValue(':purpose', $purpose, SQLITE3_TEXT);
            $query->bindValue(':lab', $lab_option, SQLITE3_TEXT);
            $query->bindValue(':time_in', $current_time, SQLITE3_TEXT);
            $query->bindValue(':remaining_sessions', $remaining_sessions, SQLITE3_INTEGER);
            
            $result = $query->execute();
            
            if ($result) {
                $success_message = "SUCCESSFULLY SITIN";
            } else {
                $error_message = "UNABLE TO SITIN";
            }
        }
    } else {
        $error_message = "FILL OUT ALL THE FIELDS";
    }
}


function resetSessionCount($student_id) {
    global $db; // Declare $db as global inside the function

    // Update the remaining session count for the student to 30
    $update_query = $db->prepare("UPDATE sitin_student SET remaining_sessions = 30 WHERE id_number = :id_number");
    $update_query->bindValue(':id_number', $student_id, SQLITE3_TEXT);
    $update_result = $update_query->execute();

    // Check if the update was successful
    if ($update_result) {
        return true;
    } else {
        return false;
    }
}

// When calling resetSessionCount, you no longer need to pass $db as a parameter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_session'])) {
    $student_id = $_POST['id_number']?? '';
    
    // Check if the student ID is provided
    if ($student_id) {
        // Call the function to reset the session count
        if (resetSessionCount($student_id)) {
            $success_message = "Session reset successfully!";
        } else {
            $error_message = "Failed to reset session count";
        }
    } else {
        $error_message = "Student ID is required";
    }
}

function resetAllSessions() {
    global $db; // Declare $db as global inside the function

    // Update the remaining session count for all students to 30
    $update_query = $db->prepare("UPDATE sitin_student SET remaining_sessions = 30");
    $update_result = $update_query->execute();

    // Check if the update was successful
    if ($update_result) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_all_sessions'])) {
    // Call the function to reset session count for all students
    if (resetAllSessions()) {
        $success_message = "All student sessions reset successfully!";
    } else {
        $error_message = "Failed to reset all student sessions";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $student_id = $_POST['id_number'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if ($student_id && $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_password_query = $db->prepare("UPDATE student SET password = :password WHERE id_number = :id_number");
        $update_password_query->bindValue(':password', $hashed_password, SQLITE3_TEXT);
        $update_password_query->bindValue(':id_number', $student_id, SQLITE3_TEXT);
        $update_password_result = $update_password_query->execute();

        if ($update_password_result) {
            $success_message = "Password reset successfully!";
        } else {
            $error_message = "Failed to reset password";
        }
    } else {
        $error_message = "Student ID and new password are required";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
        <style>
            .transition-pop-out {
            animation: pop-out 0.7s ease forwards;
        }

        @keyframes pop-out {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        </style>
</head>

<body class="flex min-h-screen bg-gray-800 font-mono text-white">

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

    <div class="flex-1 px-8 py-6">
        <button id="menu-toggle" class="focus:outline-none">
            <svg class="h-6 w-6 text-white hover:text-gray-200" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M4 6H20M4 12H20M4 18H11Z"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </button>

        <center>
            <h2 class="text-6xl font-semibold mb-6 text-green-400">Search Students</h2>
        </center>
        <div class="flex justify-center mb-8">
            <form method="GET" action="" class="w-full max-w-xl">
                <div class="flex items-center border-b border-b-2 border-green-500 py-2">
                    <input name="search_id" class="appearance-none bg-transparent border-none w-full text-white mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="Enter ID number">
                    <button type="submit" class="flex-shrink-0 bg-green-500 hover:bg-green-700 border-green-500 hover:border-green-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <form method="POST" action="">
    <button type="submit" name="reset_all_sessions" class="mt-4 block w-full bg-gray-900 hover:bg-gray-500 text-red-400 hover:text-white uppercase tracking-wider font-semibold rounded-md py-2">Reset All Students Sessions</button>
</form>

        <div class="mt-8">
            <?php
            $db = new SQLite3('sitin.db');

            if (isset($_GET['search_id'])) {
                $search_id = $_GET['search_id'];

                $query = $db->prepare("
                    SELECT id_number, firstname, lastname, email 
                    FROM student 
                    WHERE id_number = :id"
                );
                $query->bindParam(':id', $search_id);
                $result = $query->execute();

                if ($row = $result->fetchArray()) {
                    $remaining_sessions = 30; 
                    $session_query = $db->prepare("SELECT remaining_sessions FROM sitin_student WHERE id_number = :student_id");
                    $session_query->bindValue(':student_id', $row['id_number'], SQLITE3_TEXT);
                    $session_result = $session_query->execute();
                    $session_row = $session_result->fetchArray();
                    if ($session_row) {
                        $remaining_sessions = $session_row['remaining_sessions'];
                    }

                
                    ?>
                    <div class="max-w-lg mx-auto bg-gray-600 rounded-xl shadow-md overflow-hidden md:max-w-base font-mono transition-pop-out">
                        <div class="md:flex">

                            <div class="p-8 align-middle">
                                <div class="uppercase tracking-wide text-3xl text-green-500 font-semibold">ID NO: <?php echo $row['id_number']; ?></div>
                                <div class="block mt-1 text-xl leading-tight font-medium text-white capitalize">Name: <?php echo $row['firstname'] . " " . $row['lastname']; ?></div>
                                <p class="mt-2 text-white text-xl">Email: <?php echo $row['email']; ?></p>
                                <form method="POST" action="">
                                    <input type="hidden" name="student_id" value="<?php echo $row['id_number']; ?>">
                                    <input type="hidden" name="firstname" value="<?php echo $row['firstname']; ?>">
                                    <input type="hidden" name="lastname" value="<?php echo $row['lastname']; ?>">
                                    <h3 class="text-white text-lg mt-4">PURPOSE</h3>
                                    <select name="purpose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-black" required>
                                        <option value="">Select a Purpose</option>
                                        <option value="Python">Python</option>
                                        <option value="java">Java</option>
                                        <option value="Elnet ">Elnet</option>
                                        <option value="C#">C#</option>
                                        <option value="android">Android</option>
                                    </select>
                                    <h3 class="text-white text-lg mt-4">COMPUTER LABORATORY</h3>
                                    <select name="lab_option" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-black" required>
                                        <option value="">Select Lab Option</option>
                                        <option value="lab 524">Lab 524</option>
                                        <option value="lab 525">Lab 525</option>
                                        <option value="lab 526">Lab 526</option>
                                        <option value="lab 528">Lab 528</option>
                                        <option value="lab 542">Lab 542</option>
                                    </select>
                                    <h3 class="text-white text-lg mt-4">REMAINING SESSIONS</h3>
                                    <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 px-3 py-2  text-white"><?php echo $remaining_sessions; ?></div>
                                    <button type="submit" name="submit_sitin" class="mt-4 block w-full bg-gray-900 hover:bg-gray-500 text-green-400 hover:text-red-400 uppercase tracking-wider font-semibold rounded-md py-2">SITIN</button>
                                    
                                </form>
                                
                                <form method="POST" action="">
                                    <input type="hidden" name="id_number" value="<?php echo $row['id_number']; ?>">
                                    <button type="submit" name="reset_session" class="mt-4 block w-full bg-gray-900 hover:bg-gray-500 text-red-400 hover:text-white uppercase tracking-wider font-semibold rounded-md py-2">Reset Session</button>
                                </form>
                                

                                <form id="passwordForm" method="POST" action="">
                                    <input type="hidden" name="id_number" value="<?php echo $row['id_number'];?>">
                                    <h3 class="text-white text-lg mt-4">NEW PASSWORD</h3>
                                    <div class="flex">
                                        <input type="password" name="new_password" id="newPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-black" required>
                                    </div>
                                    <h3 class="text-white text-lg mt-4">CONFIRM PASSWORD</h3>
                                    <div class="flex">
                                        <input type="password" name="confirm_password" id="confirmPassword" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-black" required>
                                    </div>
                                    <p id="passwordMismatch" style="color:red; display:none;">Passwords do not match.</p>
                                    <button type="submit" name="reset_password" class="mt-4 block w-full bg-gray-900 hover:bg-gray-500 text-red-400 hover:text-white uppercase tracking-wider font-semibold rounded-md py-2">Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<center><p class='text-red-500'>No student found with the provided ID.</p></center> ";
                    echo "<script>alert('No student found with the provided ID.');</script>";
                }
            }
            ?>
            <script>
                
                <?php if ($success_message): ?>
                alert('<?php echo $success_message; ?>');
                <?php endif; ?>
            </script>
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

    document.getElementById('passwordForm').addEventListener('submit', function(event) {
    var newPassword = document.getElementById('newPassword').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword!== confirmPassword) {
        event.preventDefault();
        document.getElementById('passwordMismatch').style.display = 'block';
    } else {0
        document.getElementById('passwordMismatch').style.display = 'none';
    }
});
  </script>
</body>

</html>
