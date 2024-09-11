<?php
require_once('../authen.php');

// ตรวจสอบว่ามีการส่ง promotion_id มาหรือไม่
if (isset($_GET['promotion_id'])) {
    $promotion_id = $_GET['promotion_id'];

    // ดึงข้อมูลโปรโมชั่นจากฐานข้อมูล
    try {
        $stmt = $conn->prepare("SELECT * FROM promotion WHERE promotion_id = :promotion_id");
        $stmt->bindParam(':promotion_id', $promotion_id, PDO::PARAM_INT);
        $stmt->execute();
        $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$promotion) {
            echo "ไม่พบข้อมูลโปรโมชั่นที่ต้องการแก้ไข";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "ไม่พบรหัสโปรโมชั่น";
    exit;
}

// ถ้ามีการส่งฟอร์มมา (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promotion_name = $_POST['promotion_name'];
    $detail = $_POST['detail'];
    $date_start = $_POST['date_start'];
    $date_end = $_POST['date_end'];

    // อัพเดตข้อมูลในฐานข้อมูล
    try {
        $stmt = $conn->prepare("UPDATE promotion SET promotion_name = :promotion_name, detail = :detail, date_start = :date_start, date_end = :date_end WHERE promotion_id = :promotion_id");
        $stmt->bindParam(':promotion_name', $promotion_name);
        $stmt->bindParam(':detail', $detail);
        $stmt->bindParam(':date_start', $date_start);
        $stmt->bindParam(':date_end', $date_end);
        $stmt->bindParam(':promotion_id', $promotion_id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: index.php?edit=success');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขข้อมูลลูกค้า</title>
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
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fas fa-tag"></i>
                                        แก้ไขข้อมูลโปรโมชั่น
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form id="formData" action="update_promotion.php" method="post"
                                        style="padding: 20px;">
                                        <input type="hidden" name="promotion_id"
                                            value="<?php echo htmlspecialchars($promotion['promotion_id']); ?>">
                                        <div class="form-group">
                                            <label for="promotion_name">ชื่อโปรโมชั่น</label>
                                            <input type="text" class="form-control" id="promotion_name"
                                                name="promotion_name"
                                                value="<?php echo htmlspecialchars($promotion['promotion_name']); ?>"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="detail">รายละเอียด</label>
                                            <textarea class="form-control" id="detail" name="detail"
                                                required><?php echo htmlspecialchars($promotion['detail']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_start">วันที่เริ่มโปรโมชั่น</label>
                                            <input type="date" class="form-control" id="date_start" name="date_start"
                                                value="<?php echo htmlspecialchars($promotion['date_start']); ?>"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_end">วันที่สิ้นสุดโปรโมชั่น</label>
                                            <input type="date" class="form-control" id="date_end" name="date_end"
                                                value="<?php echo htmlspecialchars($promotion['date_end']); ?>"
                                                required>
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
                    url: 'update_promotion.php',
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