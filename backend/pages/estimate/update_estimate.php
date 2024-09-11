<?php
require_once('../authen.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับข้อมูลจากฟอร์ม
    $estimate_id = $_POST['estimate_id'];
    $response = $_POST['response'];
    $date = $_POST['date'];
    $status = $_POST['status'];


    try {
        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE estimate SET response = :response, date = :date, status = :status, WHERE estimate_id = :estimate_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':response', $response);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':estimate_id', $estimate_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: index.php?success=1");
        } else {
            echo "Error: การอัปเดตข้อมูลล้มเหลว";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
