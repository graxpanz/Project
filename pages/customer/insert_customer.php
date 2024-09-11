<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];

    // ตรวจสอบการป้อนข้อมูล (เพิ่มเติมได้)
    if (empty($name) || empty($surname) || empty($email) || empty($phone) || empty($age)) {
        die('Please fill all fields.');
    }

    try {
        // เตรียมคำสั่ง SQL
        $stmt = $conn->prepare("INSERT INTO customer (name, surname, email, phone, age) VALUES (:name, :surname, :email, :phone, :age)");

        // ผูกค่ากับพารามิเตอร์
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);

        // ดำเนินการคำสั่ง SQL
        $stmt->execute();

        // การบันทึกสำเร็จ
        echo 'Customer added successfully.';
        header('Location: manage_customer.php'); // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลลูกค้า
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // ไม่ใช่การส่ง POST
    echo 'Invalid request method.';
}
?>
```
