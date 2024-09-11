<?php
require_once('../authen.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$estimate_id = $_GET['id'];

try {
    // ดึงข้อมูลจากตาราง estimate และเชื่อมกับตาราง customers เพื่อแสดงชื่อผู้ใช้
    $sql = "SELECT estimate.*, customer.name 
        FROM estimate 
        JOIN customer ON estimate.customer_id = customer.customer_id 
        WHERE estimate.estimate_id = :estimate_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':estimate_id', $estimate_id, PDO::PARAM_INT);
    $stmt->execute();
    $estimate = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$estimate) {
        header("Location: index.php");
        exit();
    }

    // ดึงข้อมูลลูกค้าทั้งหมดสำหรับเมนู dropdown
    $customersSql = "SELECT customer_id, name FROM customer ORDER BY name";
    $customersStmt = $conn->query($customersSql);
    $customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลการประเมินใบหน้า</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/logo.ico">
    <!-- Stylesheets -->
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
                                        <i class="fas fa-user-circle"></i>
                                        ข้อมูลการประเมินใบหน้า
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>

                                <form id="formData" action="update_estimate.php" method="post"
                                    enctype="multipart/form-data" style="padding: 20px;">
                                    <input type="hidden" name="estimate_id"
                                        value="<?php echo htmlspecialchars($estimate['estimate_id']); ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">ชื่อลูกค้า</label>
                                        <p class="form-control-plaintext">
                                            <?php echo htmlspecialchars($estimate['name']); ?>
                                        </p>

                                    </div>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">รูปประเมินใบหน้า</label>
                                        <?php if (!empty($estimate['file'])): ?>
                                            <?php
                                            $file_extension = pathinfo($estimate['file'], PATHINFO_EXTENSION);
                                            $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                                            if (in_array(strtolower($file_extension), $image_extensions)): ?>
                                                <div>
                                                    <img src="../estimate/image/<?php echo htmlspecialchars($estimate['file']); ?>"
                                                        alt="ไฟล์ประเมิน" style="max-width: 100%; height: auto;">
                                                </div>
                                            <?php else: ?>
                                                <p class="form-control-plaintext">ไฟล์ไม่ใช่รูปภาพ</p>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p class="form-control-plaintext">ไม่มีไฟล์</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="detail" class="form-label">รายละเอียด</label>
                                        <textarea class="form-control-plaintext" id="detail" name="detail" rows="3"
                                            readonly><?php echo htmlspecialchars($estimate['detail']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="response" class="form-label">การตอบกลับ</label>
                                        <textarea class="form-control" id="response" name="response"
                                            rows="3"><?php echo htmlspecialchars($estimate['response']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">ราคาที่ประเมิน</label>
                                        <textarea class="form-control" id="price" name="price"
                                            rows="3"><?php echo htmlspecialchars($estimate['price']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">วันที่ประเมิน</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo htmlspecialchars($estimate['date']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">สถานะ</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="Pending" <?php echo ($estimate['status'] == 'Pending') ? 'selected' : ''; ?>>ยังไม่ได้ตอบกลับ</option>
                                            <option value="Completed" <?php echo ($estimate['status'] == 'Completed') ? 'selected' : ''; ?>>ตอบกลับแล้ว</option>
                                        </select>
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
    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script>
        $(function () {
            $('#formData').on('submit', function (e) {
                e.preventDefault();
                var formData = new FormData(this); // ใช้ FormData สำหรับการอัปโหลดไฟล์
                $.ajax({
                    type: 'POST',
                    url: 'update_estimate.php',
                    data: formData,
                    processData: false,
                    contentType: false
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