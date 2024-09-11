<?php
require_once('../authen.php');

if (!isset($_GET['service_id'])) {
    header("Location: manage_service.php");
    exit();
}

$service_id = $_GET['service_id'];

// ใช้ PDO เพื่อดึงข้อมูลการบริการที่ต้องการแก้ไข
$sql = "SELECT * FROM service WHERE service_id = :service_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['service_id' => $service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "ข้อมูลไม่พบ";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขข้อมูลการบริการ</title>
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
                                        <i class="fas fa-air-freshener"></i>
                                        เพิ่มข้อมูลการบริการ
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>

                                <form id="formData" action="update_service.php" method="post" style="padding: 20px;">
                                    <input type="hidden" name="service_id"
                                        value="<?php echo htmlspecialchars($service['service_id']); ?>">
                                    <div class="mb-3">
                                        <label for="service_name" class="form-label">ชื่อการบริการ</label>
                                        <input type="text" class="form-control" id="service_name" name="service_name"
                                            value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="service_type_id" class="form-label">ประเภทการบริการ</label>
                                        <select class="form-control" id="service_type_id" name="service_type_id"
                                            required>
                                            <?php
                                            // ใช้ PDO ดึงประเภทบริการ
                                            $sql = "SELECT * FROM service_type ORDER BY service_type_name";
                                            $stmt = $conn->query($sql);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $selected = ($row['service_type_id'] == $service['service_type_id']) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($row['service_type_id']) . "' $selected>" . htmlspecialchars($row['service_type_name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="service_price" class="form-label">ราคา(บาท)</label>
                                        <input type="number" class="form-control" id="service_price"
                                            name="service_price"
                                            value="<?php echo htmlspecialchars($service['service_price']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="service_time" class="form-label">เวลาในการบริการ(นาที)</label>
                                        <input type="number" class="form-control" id="service_time" name="service_time"
                                            value="<?php echo htmlspecialchars($service['service_time']); ?>" required>
                                        <div id="service_time_help" class="form-text">
                                            กรุณากรอกจำนวนเวลาในการให้บริการเป็นนาทีเท่านั้น
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="service_detail" class="form-label">รายละเอียด</label>
                                        <input type="text" class="form-control" id="service_detail"
                                            name="service_detail"
                                            value="<?php echo htmlspecialchars($service['service_detail']); ?>"
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
                    url: 'update_service.php',
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