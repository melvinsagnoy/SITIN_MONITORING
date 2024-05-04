<?php
// update_session.php

// Assuming you have the necessary database connection here

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    // Get the student ID from the AJAX request
    $student_id = $_POST['student_id'];

    // Update the remaining session count for the student to 30
    $update_query = $db->prepare("UPDATE sitin_student SET remaining_sessions = 30 WHERE id_number = :student_id");
    $update_query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
    $update_result = $update_query->execute();

    // Check if the update was successful
    if ($update_result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to update session count'));
    }
}
?>