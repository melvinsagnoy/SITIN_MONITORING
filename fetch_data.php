<?php
require_once 'db_connection.php'; // Adjust the path as necessary

if (isset($_GET['date']) &&!empty($_GET['date'])) {
    $selectedDate = $_GET['date'];

    $query = $db->prepare("SELECT purpose, COUNT(*) as count, MIN(time_in) as time_in, MIN(time_out) as time_out
                           FROM sitin_student
                           WHERE DATE(time_in) =?
                           GROUP BY purpose");
    $query->bindValue(1, $selectedDate, SQLITE3_TEXT);
    $result = $query->execute();

    $data = $result->fetchAll(SQLITE3_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Invalid request']);
}
?>
