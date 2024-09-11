<?php
require_once('../authen.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $stock_id = $_POST['stock_id'];
    $stock_name = $_POST['stock_name'];
    $service_type_id = $_POST['service_type_id'];
    $amount = $_POST['amount'];
    
    // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
    $new_image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // กำหนดประเภทไฟล์ที่อนุญาต
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedExtensions)) {
            // สร้างชื่อไฟล์ใหม่
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = '../../uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            // อัปโหลดไฟล์
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $new_image = $newFileName;
            } else {
                echo "<script>alert('ไม่สามารถอัปโหลดไฟล์ได้'); window.location.href = './';</script>";
                exit();
            }
        } else {
            echo "<script>alert('ประเภทไฟล์ไม่ถูกต้อง'); window.location.href = './';</script>";
            exit();
        }
    }

    try {
        // เตรียมคำสั่ง SQL
        if ($new_image) {
            // อัปเดตข้อมูลพร้อมกับรูปภาพใหม่
            $sql = "UPDATE stock SET stock_name = :stock_name, service_type_id = :service_type_id, amount = :amount, image = :image WHERE stock_id = :stock_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':image', $new_image, PDO::PARAM_STR);
        } else {
            // อัปเดตข้อมูลโดยไม่เปลี่ยนแปลงรูปภาพ
            $sql = "UPDATE stock SET stock_name = :stock_name, service_type_id = :service_type_id, amount = :amount WHERE stock_id = :stock_id";
            $stmt = $conn->prepare($sql);
        }
        
        $stmt->bindParam(':stock_id', $stock_id, PDO::PARAM_INT);
        $stmt->bindParam(':stock_name', $stock_name, PDO::PARAM_STR);
        $stmt->bindParam(':service_type_id', $service_type_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        
        // อัปเดตข้อมูลในฐานข้อมูล
        if ($stmt->execute()) {
            echo "<script>alert('อัปเดตข้อมูลเรียบร้อย'); window.location.href = './';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล'); window.location.href = './';</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ไม่สามารถดำเนินการได้'); window.location.href = './';</script>";
}
?>
