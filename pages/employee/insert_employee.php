<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $bdate = $_POST['bdate'];
    $age = $_POST['age'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $positions = $_POST['position']; // This is an array of selected positions

    try {
        // Insert employee data
        $stmt = $conn->prepare("INSERT INTO employees (fname, lname, bdate, age, tel, email, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $bdate, $age, $tel, $email, $address]);

        // Get the last inserted employee ID
        $emp_id = $conn->lastInsertId();

        // Insert positions into employees_position table
        $stmt = $conn->prepare("INSERT INTO employees_position (emp_id, position_id) VALUES (?, ?)");
        foreach ($positions as $position_id) {
            $stmt->execute([$emp_id, $position_id]);
        }

        // Redirect to manage employees page or display success message
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
```
