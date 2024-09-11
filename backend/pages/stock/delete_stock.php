<?php
require_once('../authen.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Stock ID is missing.');
}

$stock_id = $_GET['id'];

try {
    // เตรียมคำสั่ง SQL สำหรับการลบ
    $stmt = $conn->prepare("DELETE FROM stock WHERE stock_id = :id");
    
    // ผูกค่ากับพารามิเตอร์
    $stmt->bindParam(':id', $stock_id, PDO::PARAM_INT);
    
    // ดำเนินการคำสั่ง SQL
    $stmt->execute();
    
    // การลบสำเร็จ
    echo 'tock deleted successfully.';
    header('Location: index.php?delete=success'); // เปลี่ยนเส้นทางไปยังหน้าแสดงข้อมูลลูกค้า
    exit();
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
