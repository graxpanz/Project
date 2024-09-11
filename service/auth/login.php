<?php
session_start(); // Start the session if not already started
require_once '../connect.php';

$username = $_POST['username'];
$password = $_POST['password'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีข้อมูลใน $row หรือไม่
    if ($row && $password == $row['password']) {
        $_SESSION['AD_ID'] = $row['u_id'];
        $_SESSION['AD_FIRSTNAME'] = $row['firstname'];
        $_SESSION['AD_LASTNAME'] = $row['lastname'];
        $_SESSION['AD_USERNAME'] = $row['username'];

        $_SESSION['AD_IMAGE'] = $row['image'];
        $_SESSION['AD_STATUS'] = $row['status'];
        $_SESSION['AD_LOGIN'] = date('Y-m-d H:i:s');

        header('location:../../pages/dashboard/index.php');
        exit();
    } else {
        echo '<script>
                alert("รหัสผ่านไม่ถูกต้องหรือชื่อผู้ใช้ไม่ถูกต้อง");
                window.location.href = "../../login.php";
              </script>';
        exit();
    }
} else {
    echo '<script>
            alert("ไม่พบชื่อผู้ใช้นี้ในระบบ");
            window.location.href = "../../login.php";
          </script>';
    exit();
}
?>