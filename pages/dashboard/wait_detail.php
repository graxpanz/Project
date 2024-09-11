<?php
require_once('../authen.php');

if (!isset($_GET['id'])) {
    die('Queue ID is missing.');
}

$queue_id = $_GET['id'];

try {
    // เตรียมคำสั่ง SQL สำหรับการดึงข้อมูลการจองคิวที่สถานะเป็น pending
    $stmt = $conn->prepare("SELECT * FROM queue WHERE queue_id = :id AND status = 'Pending'");
    $stmt->bindParam(':id', $queue_id, PDO::PARAM_INT);
    $stmt->execute();
    
    // ดึงข้อมูลการจองคิว
    $queue = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีการจองคิวหรือไม่
    if (!$queue) {
        die('Queue not found or not in pending status.');
    }
} catch (PDOException $e) {
    // แสดงข้อผิดพลาดหากเกิดปัญหาในการเชื่อมต่อฐานข้อมูล
    die('Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>รายละเอียดการจองคิว</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/logo.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php') ?>
        <div class="content-wrapper pt-3">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-users"></i>
                                        รายละเอียดการจองคิว
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>

                                <form id="formData" action="update_queue.php" method="post" style="padding: 20px;">
                                    <input type="hidden" name="queue_id"
                                        value="<?php echo htmlspecialchars($queue['queue_id']); ?>">
                                    <div class="mb-3">
                                        <label for="queue_date" class="form-label">วันที่จอง</label>
                                        <input type="date" class="form-control" id="queue_date" name="queue_date"
                                            value="<?php echo htmlspecialchars($queue['queue_date']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="queue_time" class="form-label">เวลาที่จอง</label>
                                        <input type="time" class="form-control" id="queue_time" name="queue_time"
                                            value="<?php echo htmlspecialchars($queue['queue_time']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">สถานะ</label>
                                        <input type="text" class="form-control" id="status" name="status"
                                            value="<?php echo htmlspecialchars($queue['status']); ?>" readonly>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block mx-auto w-50"
                                            name="submit">บันทึกข้อมูล</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('../includes/footer.php') ?>
    </div>
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script>
        $(function () {
            $('#formData').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'update_queue.php',
                    data: $('#formData').serialize()
                }).done(function (resp) {
                    Swal.fire({
                        text: 'อัพเดทข้อมูลเรียบร้อย',
                        icon: 'success',
                        confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        location.assign('./');
                    });
                }).fail(function () {
                    Swal.fire({
                        text: 'การอัพเดทข้อมูลล้มเหลว',
                        icon: 'error',
                        confirmButtonText: 'ตกลง',
                    });
                });
            });
        });
    </script>
</body>

</html>
