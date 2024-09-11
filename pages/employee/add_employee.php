<?php
require_once('../authen.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าเพิ่มข้อมูลพนักงาน</title>
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
                                        เพิ่มข้อมูลพนักงาน
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form action="insert_employee.php" method="POST">
                                        <div class="mb-3">
                                            <label for="fname" class="form-label">ชื่อ</label>
                                            <input type="text" class="form-control" id="fname" name="fname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lname" class="form-label">นามสกุล</label>
                                            <input type="text" class="form-control" id="lname" name="lname" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bdate" class="form-label">วัน/เดือน/ปีเกิด</label>
                                            <input type="date" class="form-control" id="bdate" name="bdate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="age" class="form-label">อายุ</label>
                                            <input type="number" class="form-control" id="age" name="age" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tel" class="form-label">เบอร์ติดต่อ</label>
                                            <input type="text" class="form-control" id="tel" name="tel" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">ที่อยู่</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label>เลือกตำแหน่งงานบริการ</label><br>
                                            <?php
                                            try {
                                                $sql = "SELECT * FROM position_type";
                                                $stmt = $conn->query($sql);
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="position'
                                                        . htmlspecialchars($row['position_id'])
                                                        . '" name="position[]" value="'
                                                        . htmlspecialchars($row['position_id']) . '">
                                                <label class="form-check-label" for="position'
                                                        . htmlspecialchars($row['position_id']) . '">'
                                                        . htmlspecialchars($row['position_name'])
                                                        . '</label>
                                            </div>';
                                                }
                                            } catch (PDOException $e) {
                                                echo 'Error: ' . $e->getMessage();
                                            }
                                            ?>
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