<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = $_POST['emp_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $bddate = $_POST['bdate'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    // Make sure to get the positions from the form data
    $positions = $_POST['position'];

    // Update employee data
    $sql = "UPDATE employees SET fname = ?, lname = ?, tel = ?, email = ?, bdate = ?, age = ?, address = ? WHERE emp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$fname, $lname, $tel, $email, $bddate, $age, $address, $emp_id]);

    // Delete old positions
    $sql = "DELETE FROM employees_position WHERE emp_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$emp_id]);

    // Insert new positions
    foreach ($positions as $position_id) {
        $sql = "INSERT INTO employees_position (emp_id, position_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$emp_id, $position_id]);
    }

    // Redirect after successful update
    header('Location: index.php');
    exit();
}
?>
