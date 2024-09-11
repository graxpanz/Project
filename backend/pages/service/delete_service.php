<?php
require_once('../authen.php');
if (!isset($_GET['service_id'])) {
    header("Location: index.php");
    exit();
}

$service_id = $_GET['service_id'];

// สร้างคำสั่ง SQL สำหรับลบข้อมูล
$sql = "DELETE FROM service WHERE service_id = :service_id";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);

// เชื่อมต่อข้อมูลกับตัวแปร
$stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);

// ดำเนินการคำสั่ง SQL
if ($stmt->execute()) {
    // ถ้าลบสำเร็จ ให้เปลี่ยนเส้นทางไปที่หน้าจัดการข้อมูลบริการ
    header("Location: index.php?delete=success");
    exit(); // ป้องกันการทำงานต่อหลังจากการ redirect
} else {
    // แสดงข้อความข้อผิดพลาดหากมีปัญหาในการลบ
    $errorInfo = $stmt->errorInfo();
    echo "เกิดข้อผิดพลาดในการลบข้อมูล: " . htmlspecialchars($errorInfo[2]);
}
?>
