<?php
require_once('../authen.php');

try {
    $database = new Database();
    $conn = $database->connect();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['promotion_id']) && !empty($_GET['promotion_id'])) {
            $promotion_id = $_GET['promotion_id'];

            $sql = "DELETE FROM promotion WHERE promotion_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$promotion_id]);

            header('Location: index.php?delete=success');
            exit(); 
        } else {
            echo "Error: Promotion ID is missing or empty.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
