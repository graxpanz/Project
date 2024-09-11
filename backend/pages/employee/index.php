<?php
require_once('../authen.php');

try {
    // แก้ไข SQL Query เพื่อใช้ตารางที่มีอยู่
    $sql = "SELECT employees.emp_id, employees.fname, employees.lname, employees.tel, employees.email, employees.bdate, employees.age, employees.address, position_type.position_name 
    FROM employees 
    JOIN employees_position ON employees.emp_id = employees_position.emp_id
    JOIN position_type ON employees_position.position_id = position_type.position_id";

    $stmt = $conn->query($sql);
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all positions for dropdown menu
    $positionSql = "SELECT position_id, position_name FROM position_type";
    $positionStmt = $conn->query($positionSql);
    $positions = $positionStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลพนักงาน</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/logo.ico">
    <!-- stylesheet -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <script>
        // ป้องกันการกดปุ่มย้อนกลับบนเบราว์เซอร์
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function (event) {
            history.go(1);
        };
    </script>
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
                                        จัดการข้อมูลพนักงาน
                                    </h4>
                                    <a href="add_employee.php" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i>
                                        เพิ่มข้อมูลพนักงาน
                                    </a>
                                </div>
                                <div class="card-body">
                                    <table id="logs" class="table table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ลำดับ</th>
                                                <th>ชื่อ</th>
                                                <th>นามสกุล</th>
                                                <th>วัน/เดือน/ปีเกิด</th>
                                                <th>อายุ</th>
                                                <th>เบอร์โทรศัพท์</th>
                                                <th>อีเมล</th>
                                                <th>ที่อยู่</th>
                                                <th>ตำแหน่งการบริการ</th>
                                                <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $num = 0;
                                            foreach ($employees as $emp):  // Changed $employee to $employees
                                                $num++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $num; ?></td>
                                                    <td><?php echo htmlspecialchars($emp['fname']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['lname']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['bdate']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['age']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['tel']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['address']); ?></td>
                                                    <td><?php echo htmlspecialchars($emp['position_name']); ?></td>
                                                    <td>
                                                        <a href="edit_employee.php?id=<?php echo $emp['emp_id']; ?>"
                                                            type="button" class="btn btn-warning text-white">
                                                            <i class="far fa-edit"></i> แก้ไข
                                                        </a>
                                                        <button type='button' class='btn btn-danger delete-btn'
                                                            data-id='<?php echo $emp['emp_id']; ?>'>
                                                            <i class='far fa-trash-alt'></i> ลบ
                                                        </button>

                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('../includes/footer.php') ?>
    </div>

    <!-- scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script src="../../plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="../../plugins/toastr/toastr.min.js"></script>

    <!-- datatables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <script>
        // แสดงผลการทำงานสำเร็จด้วย sweet alert
        if (window.location.search.includes('?delete=success')) {
            Swal.fire("รายการของคุณถูกลบเรียบร้อย", "", "success");
            history.replaceState(null, null, window.location.pathname);
        }
        $(function () {
            $('#logs').DataTable({
                initComplete: function () {
                    $(document).on('click', '.delete-btn', function () {
                        let emp_id = $(this).data('id');
                        Swal.fire({
                            text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `delete_employee.php?id=${emp_id}`;
                            }
                        });
                    }).on('change', '.toggle-event', function () {
                        toastr.success('อัพเดทข้อมูลเสร็จเรียบร้อย');
                    });
                },
                fnDrawCallback: function () {
                    $('.toggle-event').bootstrapToggle();
                },
                responsive: {
                    details: {
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                            tableClass: 'table'
                        })
                    }
                },
                language: {
                    "lengthMenu": "แสดงข้อมูล _MENU_ แถว",
                    "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                    "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                    "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": 'ค้นหา'
                }
            });
        });

    </script>
</body>

</html>