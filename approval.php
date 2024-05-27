<?php
// Connect to the database or include your database connection file
$db = new SQLite3('sitin.db');

// Initialize variables for error/success messages
$success_message = '';
$error_message = '';

// Fetch all booking requests
$query = $db->prepare("SELECT * FROM reservations");
$result = $query->execute();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $reservation_id = $_POST['reservation_id'] ?? '';

    if ($_POST['action'] === 'accept') {
        // Fetch the reservation details
        $query = $db->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
        $query->bindValue(':reservation_id', $reservation_id, SQLITE3_INTEGER);
        $reservation = $query->execute()->fetchArray(SQLITE3_ASSOC);

        if ($reservation) {
            // Insert data into the sitin_student table (excluding the computer field)
            $insert_query = $db->prepare("
                INSERT INTO sitin_student (id_number, firstname, lastname, purpose, lab, remaining_sessions, time_in, time_out, status)
                VALUES (:id_number, :firstname, :lastname, :purpose, :lab, :remaining_sessions, :time_in, :time_out, :status)
            ");
            $insert_query->bindValue(':id_number', $reservation['id_number'], SQLITE3_TEXT);
            $insert_query->bindValue(':firstname', $reservation['first_name'], SQLITE3_TEXT);
            $insert_query->bindValue(':lastname', $reservation['last_name'], SQLITE3_TEXT);
            $insert_query->bindValue(':purpose', $reservation['purpose'], SQLITE3_TEXT);
            $insert_query->bindValue(':lab', $reservation['lab'], SQLITE3_TEXT);
            $insert_query->bindValue(':remaining_sessions', $reservation['remaining_sessions'], SQLITE3_INTEGER);
            $insert_query->bindValue(':time_in', $reservation['time_in'], SQLITE3_TEXT);
            $insert_query->bindValue(':time_out', $reservation['time_out'], SQLITE3_TEXT);
            $insert_query->bindValue(':status', 'accepted', SQLITE3_TEXT);
            $insert_result = $insert_query->execute();

            if ($insert_result) {
                // Update the status of the reservation to 'accepted'
                $update_query = $db->prepare("UPDATE reservations SET status = 'accepted' WHERE id = :reservation_id");
                $update_query->bindValue(':reservation_id', $reservation_id, SQLITE3_INTEGER);
                $update_result = $update_query->execute();

                if ($update_result) {
                    $success_message = 'Reservation successfully accepted.';
                } else {
                    $error_message = 'Failed to update reservation status. Please try again.';
                }
            } else {
                $error_message = 'Failed to add record to sitin_student. Please try again.';
            }
        } else {
            $error_message = 'Reservation not found. Please try again.';
        }
    } elseif ($_POST['action'] === 'reject') {
        // Update the status of the reservation to 'rejected'
        $update_query = $db->prepare("UPDATE reservations SET status = 'rejected' WHERE id = :reservation_id");
        $update_query->bindValue(':reservation_id', $reservation_id, SQLITE3_INTEGER);
        $update_result = $update_query->execute();

        if ($update_result) {
            $success_message = 'Reservation successfully rejected.';
        } else {
            $error_message = 'Failed to reject reservation. Please try again.';
        }
    }

    // Redirect back to the current page after processing the action
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Approval</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-buttons button {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .accept {
            background-color: #28a745;
            color: #fff;
        }

        .reject {
            background-color: #dc3545;
            color: #fff;
        }

        a.back-link {
            text-decoration: none;
            color: #007bff;
            display: inline-block;
            margin-bottom: 20px;
        }
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
    <h1>Booking Approval</h1>

    <!-- Display success or error messages -->
    <?php if (!empty($success_message)) : ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error_message)) : ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <a href="#" onclick="history.back();" class="back-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z" />
            <path d="M14 7l-5 5 5 5V7z" />
        </svg>
        Back
    </a>

    <!-- Display all booking requests -->
    <?php if ($result && $result->numColumns() > 0) : ?>
        <table>
            <thead>
                <tr class="text-black">
                    <th>ID</th>
                    <th>ID Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Purpose</th>
                    <th>Lab</th>
                    <th>Reservation Date</th>
                    <th>Computer</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) : ?>
                    <tr class="hover:text-black">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['id_number']; ?></td>
                        <td><?php echo $row['first_name']; ?></td>
                        <td><?php echo $row['last_name']; ?></td>
                        <td><?php echo $row['purpose']; ?></td>
                        <td><?php echo $row['lab']; ?></td>
                        <td><?php echo $row['reservation_date']; ?></td>
                        <td><?php echo $row['computer']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td class="action-buttons">
                            <?php if ($row['status'] === 'pending') : ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="accept" class="accept">Accept</button>
                                    <button type="submit" name="action" value="reject" class="reject">Reject</button>
                                </form>
                            <?php else : ?>
                                <span><?php echo ucfirst($row['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No booking requests found.</p>
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