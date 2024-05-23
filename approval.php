<?php
// Connect to the database or include your database connection file
$db = new SQLite3('sitin.db');

// Initialize variables for error/success messages
$success_message = '';
$error_message = '';

// Fetch all booking requests
$query = $db->prepare("
    SELECT * FROM reservations
");
$result = $query->execute();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $reservation_id = $_POST['reservation_id'] ?? '';

    if ($_POST['action'] === 'accept') {
        // Update the status of the reservation to 'accepted'
        $query = $db->prepare("
            UPDATE reservations
            SET status = 'accepted'
            WHERE id = :reservation_id
        ");
        $query->bindValue(':reservation_id', $reservation_id, SQLITE3_INTEGER);
        $result = $query->execute();

        if ($result) {
            $success_message = 'Reservation successfully accepted.';
        } else {
            $error_message = 'Failed to accept reservation. Please try again.';
        }
    } elseif ($_POST['action'] === 'reject') {
        // Update the status of the reservation to 'rejected'
        $query = $db->prepare("
            UPDATE reservations
            SET status = 'rejected'
            WHERE id = :reservation_id
        ");
        $query->bindValue(':reservation_id', $reservation_id, SQLITE3_INTEGER);
        $result = $query->execute();

        if ($result) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff; /* Heading color */
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td button {
            padding: 5px 10px;
            margin-right: 5px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            outline: none;
            color: #fff;
        }

        .accept {
            background-color: #28a745; /* Green color for accept button */
        }

        .reject {
            background-color: #dc3545; /* Red color for reject button */
        }

        .action-buttons {
            display: flex;
        }

        /* Sidebar styles */
        #sidebar {
            transition: width 0.3s ease-in-out;
            width: 64px; /* Initial width */
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #374151; /* Sidebar background color */
            padding-top: 1rem;
            color: #fff;
            z-index: 1000;
        }

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

        .menu-toggle,
        .close-menu {
            background: transparent;
            border: none;
            cursor: pointer;
            outline: none;
            color: #fff;
        }

        .menu-toggle svg,
        .close-menu svg {
            height: 24px;
            width: 24px;
        }

        .menu-toggle {
            margin-left: 1rem;
        }

        .close-menu {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            display: none;
        }

        @media (max-width: 768px) {
            .close-menu {
                display: block;
            }
        }

        .sidebar-content ul {
            padding-left: 0;
        }

        .sidebar-content ul li {
            list-style-type: none;
            margin-bottom: 1rem;
        }

        .sidebar-content ul li a {
            display: block;
            padding: 0.75rem 1rem;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar-content ul li a:hover {
            background-color: #4b5563; /* Hover background color */
        }
    </style>
</head>
<body class="bg-gray-900">
<a href="#" onclick="history.back();">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
            <path fill="none" d="M0 0h24v24H0z" />
            <path d="M14 7l-5 5 5 5V7z" />
        </svg>
    </a>
\

        <!-- Container for content -->
        <div class="container">
            <h1>Booking Approval</h1>

            <!-- Display success or error messages -->
            <?php if (!empty($success_message)) : ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (!empty($error_message)) : ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Display all booking requests -->
            <?php if ($result && $result->numColumns() > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Number</th>
                            <th>Purpose</th>
                            <th>Lab</th>
                            <th>Reservation Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) : ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['id_number']; ?></td>
                                <td><?php echo $row['purpose']; ?></td>
                                <td><?php echo $row['lab']; ?></td>
                                <td><?php echo $row['reservation_date']; ?></td>
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
    </div>
</div>

</body>

</html>

