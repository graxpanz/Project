<?php
require_once('../authen.php');

try {
    $database = new Database();
    $conn = $database->connect();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $customer_id = $_GET['id'];

            $sql = "DELETE FROM customer WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$customer_id]);

            header('Location: index.php?delete=success');
            exit(); 
        } else {
            echo "Error: Customer ID is missing or empty.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
