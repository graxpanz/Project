<?php
require_once('../authen.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $emp_id = $_GET['id'];

            // Delete employee's positions
            $sql = "DELETE FROM employees_position WHERE emp_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$emp_id]);

            // Delete employee
            $sql = "DELETE FROM employees WHERE emp_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$emp_id]);

            header('Location: index.php?delete=success');
            exit(); 
        } else {
            echo "Error: Employee ID is missing or empty.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
