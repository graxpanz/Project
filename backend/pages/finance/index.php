<?php
require_once('../authen.php');

try {
    $stmt = $conn->prepare("SELECT * FROM finance");
    $stmt->execute();
    $finances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลการเงิน</title>
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

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: center;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: lightgreygray;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .card-body {
            padding: 20px;
        }
    </style>

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
                                        <i class="fas fa-air-freshener"></i>
                                        จัดการข้อมูลการเงิน
                                    </h4>
                                    <a href="add_service.php" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i>
                                        เพิ่มข้อมูลการเงิน
                                    </a>

                                    <!-- Dropdown สำหรับเลือกประเภทการบริการ -->
                                    <form method="GET" class="mt-3">
                                        <label for="service_type_id">เลือกประเภทการบริการ : </label>
                                        <select name="service_type_id" id="service_type_id"
                                            onchange="this.form.submit()">
                                            <option value="">ทั้งหมด</option>
                                            <?php foreach ($serviceTypes as $type): ?>
                                                <option value="<?php echo $type['service_type_id']; ?>" <?php echo isset($_GET['service_type_id']) && $_GET['service_type_id'] == $type['service_type_id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($type['service_type_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <table border="1">
                                        <thead>
                                            <tr>
                                                <th>วันที่</th>
                                                <th>รายการ</th>
                                                <th>รายรับ</th>
                                                <th>รายจ่าย</th>
                                                <th>คงเหลือ</th>
                                                <th>หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($finances as $finance): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($finance['date']); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($finance['list']); ?></td>
                                                    <td><?php echo htmlspecialchars($finance['income']); ?></td>
                                                    <td><?php echo htmlspecialchars($finance['expense']); ?></td>
                                                    <td><?php echo htmlspecialchars($finance['total']); ?></td>
                                                    <td><?php echo htmlspecialchars($finance['note']); ?></td>
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
                        let service_id = $(this).data('id');
                        Swal.fire({
                            text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `delete_service.php?service_id=${service_id}`;
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