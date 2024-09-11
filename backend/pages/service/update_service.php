<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $service_id = $_POST['service_id'];
    $service_name = $_POST['service_name'];
    $service_type_id = $_POST['service_type_id'];
    $service_price = $_POST['service_price'];
    $service_time = $_POST['service_time'];
    $service_detail = $_POST['service_detail'];

    // ตรวจสอบข้อมูลที่ส่งมาว่าครบถ้วนหรือไม่
    if (empty($service_id) || empty($service_name) || empty($service_type_id) || empty($service_price) || empty($service_time) || empty($service_detail)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit();
    }

    // สร้างคำสั่ง SQL สำหรับอัพเดตข้อมูล
    $sql = "UPDATE service SET 
                service_name = :service_name, 
                service_type_id = :service_type_id, 
                service_price = :service_price, 
                service_time = :service_time, 
                service_detail = :service_detail 
            WHERE service_id = :service_id";

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // เชื่อมต่อข้อมูลกับตัวแปร
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindParam(':service_type_id', $service_type_id, PDO::PARAM_INT);
    $stmt->bindParam(':service_price', $service_price, PDO::PARAM_STR);
    $stmt->bindParam(':service_time', $service_time, PDO::PARAM_STR);
    $stmt->bindParam(':service_detail', $service_detail, PDO::PARAM_STR);

    // ดำเนินการคำสั่ง SQL
    if ($stmt->execute()) {
        // ถ้าอัปเดตสำเร็จ ให้เปลี่ยนเส้นทางไปที่หน้าจัดการข้อมูลบริการ
        header("Location: index.php");
        exit(); // ป้องกันการทำงานต่อหลังจากการ redirect
    } else {
        // แสดงข้อความข้อผิดพลาดหากมีปัญหาในการอัปเดต
        $errorInfo = $stmt->errorInfo();
        echo "เกิดข้อผิดพลาดในการอัพเดตข้อมูล: " . htmlspecialchars($errorInfo[2]);
    }
} else {
    // ถ้าไม่ใช่ POST request ให้เปลี่ยนเส้นทางไปยังหน้าแก้ไขบริการ
    $service_id = htmlspecialchars($_GET['service_id']);
    header("Location: edit_service.php?service_id=" . $service_id);
    exit();
}
?>
