<?php
require_once('../authen.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $promotion_name = $_POST['promotion_name'];
    $detail = $_POST['detail'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];

    if (empty($promotion_name) || empty($detail) || empty($date_start) || empty($date_end)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit();
    }

    $sql = "INSERT INTO promotion (promotion_name, detail, date_start, date_end) 
            VALUES (:promotion_name, :detail, :date_start, :date_end)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':promotion_name', $promotion_name, PDO::PARAM_STR);
    $stmt->bindParam(':detail', $detail, PDO::PARAM_STR);
    $stmt->bindParam(':date_start', $date_start, PDO::PARAM_STR);
    $stmt->bindParam(':date_end', $date_end, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "เพิ่มข้อมูลโปรโมชั่นเรียบร้อย";
        header("Location: index.php");
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
    }
}
?>
