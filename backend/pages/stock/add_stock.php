<?php
require_once('../authen.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าเพิ่มข้อข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน</title>
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
                                        <i class="fas fa-users"></i>
                                        เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form action="insert_stock.php" method="POST">
                                        <div class="mb-3">
                                            <label for="stock_name" class="form-label">ชื่อสินค้า</label>
                                            <input type="text" class="form-control" id="stock_name" name="stock_name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="service_type_id"
                                                class="form-label">ประเภทสินค้าการบริการ</label>
                                            <select class="form-control" id="service_type_id" name="service_type_id">
                                                <?php
                                                include('../incloude/db_connect.php');
                                                $sql = "SELECT * FROM service_type ORDER BY service_type_name";
                                                $stmt = $conn->query($sql);
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option value='{$row['service_type_id']}'>{$row['service_type_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">จำนวนสินค้า</label>
                                            <input type="number" class="form-control" id="amount" name="amount"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="form-label">รูปภาพสินค้า</label>
                                            <input type="file" class="form-control-file" id="image" name="image"
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