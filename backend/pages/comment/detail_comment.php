<?php
require_once('../authen.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$comment_id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM comment WHERE comment_id = :comment_id");
    $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
    $stmt->execute();
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$comment) {
        echo "ไม่พบความคิดเห็นที่ต้องการ";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลการแสดงความคิดเห็นของลูกค้า</title>
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
                                        <i class="fas fa-comments"></i>
                                        ข้อมูลการแสดงความคิดเห็นของลูกค้า
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>

                                <form id="formData" action="update_comment.php" method="post"
                                    enctype="multipart/form-data" style="padding: 20px;">
                                    <input type="hidden" name="comment_id"
                                        value="<?php echo htmlspecialchars($comment['comment_id']); ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">ความคิดเห็นลูกค้า</label>
                                        <p class="form-control-plaintext">
                                            <?php echo htmlspecialchars($comment['comment']); ?>
                                        </p>
                                    </div>                                   
                                    <div class="mb-3">
                                        <label for="response" class="form-label">การตอบกลับ</label>
                                        <textarea class="form-control" id="response" name="response"
                                            rows="3"><?php echo htmlspecialchars($comment['response']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">วันที่แสดงความคิดเห็น</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo htmlspecialchars($comment['date']); ?>" required>
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
                    url: 'update_comment.php',
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