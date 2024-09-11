<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $service_name = $_POST['service_name'];
    $service_type_id = $_POST['service_type_id'];
    $service_price = $_POST['service_price'];
    $service_time = $_POST['service_time'];
    $service_detail = $_POST['service_detail'];

    // ตรวจสอบข้อมูล
    if (empty($service_name) || empty($service_type_id) || empty($service_price) || empty($service_time) || empty($service_detail)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit();
    }

    // สร้างคำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO service (service_name, service_type_id, service_price, service_time, service_detail) 
            VALUES (:service_name, :service_type_id, :service_price, :service_time, :service_detail)";

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // เชื่อมต่อข้อมูลกับตัวแปร
    $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindParam(':service_type_id', $service_type_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_price', $service_price, PDO::PARAM_STR);
    $stmt->bindParam(':service_time', $service_time, PDO::PARAM_INT);
    $stmt->bindParam(':service_detail', $service_detail, PDO::PARAM_STR);

    // ดำเนินการคำสั่ง SQL
    if ($stmt->execute()) {
        echo "เพิ่มข้อมูลเรียบร้อย";
        // Redirect หรือแสดงข้อความสำเร็จ
        header("Location: index.php");
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
    }
}
?>
```
