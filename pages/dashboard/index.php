<?php
require_once('../authen.php');

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
    SELECT 
        q.queue_id,
        c.name AS name,
        c.surname AS surname,
        c.phone AS phone,
        st.service_type_name,
        s.service_name,
        e.fname,
        q.queue_date,
        q.queue_time,
        q.status
    FROM queue q
    JOIN customer c ON q.customer_id = c.customer_id
    JOIN service s ON q.service_id = s.service_id
    JOIN employees e ON q.emp_id = e.emp_id
    JOIN service_type st ON q.service_type_id = st.service_type_id
";

    // Prepare and execute SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all data
    $queues = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>หน้าหลัก | Mira ศูนย์ความงามครบวงจร</title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/logo.ico">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="../../assets/css/adminlte.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <script>
        // Prevent browser back button
        window.history.pushState(null, null, window.location.href);
        window.onpopstate = function (event) {
            history.go(1);
        };
    </script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include_once('../includes/sidebar.php'); ?>
        <div class="content-wrapper pt-3">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-address-book"></i> สถานะการจองคิว
                                    </h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-6 mb-4">
                                        <div class="small-box bg-warning shadow">
                                            <div class="inner text-center">
                                                <h1 class="py-3">รอดำเนินการ</h1>
                                                <p>จำนวนการจองที่รอดำเนินการ</p>
                                            </div>
                                            <a href="wait_detail.php" class="small-box-footer py-3"> รายละเอียด
                                                <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6 mb-4">
                                        <div class="small-box bg-primary border border-primary shadow-sm">
                                            <div class="inner text-center">
                                                <h1 class="py-3">ยืนยันแล้ว</h1>
                                                <p>จำนวนการจองที่ยืนยันแล้ว</p>
                                            </div>
                                            <a href="../confirm_detail/" class="small-box-footer py-3"> รายละเอียด
                                                <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6 mb-4">
                                        <div class="small-box bg-success border border-success shadow-sm">
                                            <div class="inner text-center">
                                                <h1 class="py-3">เสร็จสิ้น</h1>
                                                <p>จำนวนการจองที่เสร็จสิ้น</p>
                                            </div>
                                            <a href="../success_detail/" class="small-box-footer py-3"> รายละเอียด
                                                <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6 mb-4">
                                        <div class="small-box bg-danger border border-danger shadow-sm">
                                            <div class="inner text-center">
                                                <h1 class="py-3">ยกเลิก</h1>
                                                <p>จำนวนการจองที่ยกเลิก</p>
                                            </div>
                                            <a href="../cancel_detail/" class="small-box-footer py-3"> รายละเอียด
                                                <i class="fas fa-arrow-circle-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header border-0 pt-4">
                                    <h4>
                                        <i class="fas fa-list-ul"></i>
                                        รายการจองคิวทั้งหมด
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <table id="logs" class="table table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ลำดับ</th>
                                                <th>ชื่อ</th>
                                                <th>นามสกุล</th>
                                                <th>เบอร์ติดต่อ</th>
                                                <th>ประเภทการบริการ</th>
                                                <th>การบริการ</th>
                                                <th>พนักงาน</th>
                                                <th>วันที่จอง</th>
                                                <th>เวลาที่จอง</th>
                                                <th>สถานะ</th>
                                                <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $num = 0;
                                            foreach ($queues as $queue):
                                                $num++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $num; ?></td>
                                                    <td><?php echo htmlspecialchars($queue['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['surname']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['service_type_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['service_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['fname']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['queue_date']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['queue_time']); ?></td>
                                                    <td><?php echo htmlspecialchars($queue['status']); ?></td>
                                                    <td>
                                                        <button class="btn btn-info detail-btn"
                                                            onclick="window.location.href='detail_queue.php?id=<?php echo htmlspecialchars($queue['queue_id']); ?>'">
                                                            <i class="far fa-edit"></i> รายละเอียด
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
        <?php include_once('../includes/footer.php'); ?>
    </div>

    <!-- Scripts -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../assets/js/adminlte.min.js"></script>
    <script src="../../plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="../../plugins/toastr/toastr.min.js"></script>
    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- OPTIONAL SCRIPTS -->
    <script src="../../plugins/chart.js/Chart.min.js"></script>
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
                        let stock_id = $(this).data('id');
                        Swal.fire({
                            text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ใช่',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `delete_stock.php?id=${stock_id}`;
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