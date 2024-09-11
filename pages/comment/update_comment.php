<?php
require_once('../authen.php');

if (isset($_POST['submit'])) {
    // Get POST data
    $comment_id = $_POST['comment_id'];
    $response = $_POST['response'];
    $date = $_POST['date'];

    try {
        // Prepare the update statement
        $stmt = $conn->prepare("UPDATE comment SET response = :response, date = :date WHERE comment_id = :comment_id");
        $stmt->bindParam(':response', $response);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

        // Execute the update
        if ($stmt->execute()) {
            // Success response
            echo json_encode(['status' => 'success']);
        } else {
            // Failure response
            echo json_encode(['status' => 'error']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    // Redirect if accessed directly
    header("Location: index.php");
    exit();
}
