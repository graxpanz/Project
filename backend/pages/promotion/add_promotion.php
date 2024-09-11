<?php
require_once('../authen.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าเพิ่มข้อมูลโปรโมชั่น</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/logo.ico">
    <!-- stylesheet -->
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
                                        เพิ่มข้อมูลโปรโมชั่น
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form action="insert_promotion.php" method="POST">
                                        <div class="mb-3">
                                            <label for="promotion_name" class="form-label">ชื่อโปรโมชั่น</label>
                                            <input type="text" class="form-control" id="promotion_name" name="promotion_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="detail" class="form-label">รายละเอียด</label>
                                            <input type="text" class="form-control" id="detail"
                                                name="detail" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_start" class="form-label">วันที่เริ่มโปรโมชั่น</label>
                                            <input type="date" class="form-control" id="date_start" name="date_start"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_end" class="form-label">วันที่สิ้นสุดโปรโมชั่น</label>
                                            <input type="date" class="form-control" id="date_end" name="date_end"
                                                required>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary btn-block mx-auto w-50"
                                                name="submit">บันทึกข้อมูล</button>
                                        </div>
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
    <!-- SCRIPTS -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
</body>

</html>