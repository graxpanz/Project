<?php
require_once('../authen.php');

$emp_id = $_GET['id'] ?? null;

if ($emp_id) {
    try {
        $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = ?");
        $stmt->execute([$emp_id]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$employee) {
            die("Employee not found");
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("No employee ID provided");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แก้ไขข้อมูลพนักงาน</title>
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
                                        เพิ่มข้อมูลลูกค้า
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                
                                <form id="formData" action="update_employee.php" method="post" style="padding: 20px;">
                                    <input type="hidden" name="emp_id" value="<?php echo htmlspecialchars($emp_id); ?>">
                                    <div class="mb-3">
                                        <label for="fname" class="form-label">ชื่อ</label>
                                        <input type="text" class="form-control" id="fname" name="fname"
                                            value="<?php echo htmlspecialchars($employee['fname']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lname" class="form-label">นามสกุล</label>
                                        <input type="text" class="form-control" id="lname" name="lname"
                                            value="<?php echo htmlspecialchars($employee['lname']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="tel" class="form-label">เบอร์ติดต่อ</label>
                                        <input type="tel" class="form-control" id="tel" name="tel"
                                            value="<?php echo htmlspecialchars($employee['tel']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bdate" class="form-label">วัน/เดือน/ปีเกิด</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate"
                                            value="<?php echo htmlspecialchars($employee['bdate']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="age" class="form-label">อายุ</label>
                                        <input type="number" class="form-control" id="age" name="age"
                                            value="<?php echo htmlspecialchars($employee['age']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">ที่อยู่</label>
                                        <textarea class="form-control" id="address" name="address"
                                            required><?php echo htmlspecialchars($employee['address']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>เลือกตำแหน่งงานบริการ</label><br>
                                        <?php
                                        try {
                                            // Fetch all positions
                                            $sql = "SELECT * FROM position_type";
                                            $stmt = $conn->query($sql);

                                            // Fetch selected positions for the employee
                                            $selected_positions_stmt = $conn->prepare("SELECT position_id FROM employees_position WHERE emp_id = ?");
                                            $selected_positions_stmt->execute([$emp_id]);
                                            $selected_positions = $selected_positions_stmt->fetchAll(PDO::FETCH_COLUMN);

                                            // Loop through all positions and generate checkboxes
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $checked = in_array($row['position_id'], $selected_positions) ? ' checked' : '';
                                                echo '<div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="position'
                                                    . htmlspecialchars($row['position_id'])
                                                    . '" name="position[]" value="'
                                                    . htmlspecialchars($row['position_id']) . '"' . $checked . '>
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
                    url: 'update_employee.php',
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