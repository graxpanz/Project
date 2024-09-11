<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $promotion_id = $_POST['promotion_id'];
    $promotion_name = $_POST['promotion_name'];
    $detail = $_POST['detail'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];

    if (empty($promotion_id) || empty($promotion_name) || empty($detail) || empty($date_start) || empty($date_end)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit();
    }

    $sql = "UPDATE promotion SET 
                promotion_name = :promotion_name, 
                detail = :detail, 
                date_start = :date_start, 
                date_end = :date_end 
            WHERE promotion_id = :promotion_id";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':promotion_id', $promotion_id, PDO::PARAM_INT);
    $stmt->bindParam(':promotion_name', $promotion_name, PDO::PARAM_STR);
    $stmt->bindParam(':detail', $detail, PDO::PARAM_STR);
    $stmt->bindParam(':date_start', $date_start, PDO::PARAM_STR);
    $stmt->bindParam(':date_end', $date_end, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "เกิดข้อผิดพลาดในการอัพเดตข้อมูล: " . htmlspecialchars($errorInfo[2]);
    }
} else {
    $promotion_id = htmlspecialchars($_GET['promotion_id']);
    header("Location: edit_promotion.php?promotion_id=" . $promotion_id);
    exit();
}
?>
