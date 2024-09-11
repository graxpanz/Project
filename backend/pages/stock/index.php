<?php
require_once('../authen.php');

try {
    $sql = "SELECT stock.stock_id, stock.stock_name, stock.service_type_id, stock.amount, stock.image, service_type.service_type_name 
            FROM stock 
            JOIN service_type ON stock.service_type_id = service_type.service_type_id";
    $stmt = $conn->query($sql);
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all service types for dropdown menu
    $serviceTypeSql = "SELECT service_type_id, service_type_name FROM service_type";
    $serviceTypeStmt = $conn->query($serviceTypeSql);
    $serviceTypes = $serviceTypeStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน </title>
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
                                        <i class="fas fa-box"></i>
                                        จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
                                    </h4>
                                    <a href="add_stock.php" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i>
                                        เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
                                    </a>
                                    <a href="manage_add_stock.php" class="btn btn-success mt-3">
                                        <i class="fas fa-plus"></i>
                                        เพิ่มจำนวนสินค้าและผลิตภัณฑ์ภายในร้าน
                                    </a>
                                    <a href="manage_use_stock.php" class="btn btn-danger mt-3">
                                        <i class="fas fa-minus"></i>
                                        บันทึกการใช้สินค้าและการขายผลิตภัณฑ์ภายในร้าน
                                    </a>
                                    <a href="order_stock.php" class="btn btn-warning mt-3">
                                        <i class="fas fa-file-alt"></i> ออกใบสั่งซื้อสินค้า
                                    </a>

                                    <form method="GET" class="mt-3">
                                        <label for="service_type_id">เลือกประเภทสินค้าการบริการ : </label>
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
                                    <table id="logs" class="table table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ลำดับ</th>
                                                <th>ชื่อสินค้า</th>
                                                <th>ประเภทสินค้าการบริการ</th>
                                                <th>จำนวน</th>
                                                <th>รูปสินค้า</th>
                                                <th>จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $num = 0;
                                            $filteredStocks = isset($_GET['service_type_id']) && $_GET['service_type_id'] != ''
                                                ? array_filter($stocks, function ($stock) {
                                                    return $stock['service_type_id'] == $_GET['service_type_id'];
                                                })
                                                : $stocks;
                                            foreach ($filteredStocks as $stock):
                                                $num++;
                                                ?>
                                                <tr>
                                                    <td><?php echo $num; ?></td>
                                                    <td><?php echo htmlspecialchars($stock['stock_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($stock['service_type_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($stock['amount']); ?></td>
                                                    <td><img src="../stock/image/<?php echo htmlspecialchars($stock['image']); ?>"
                                                            alt="Stock Image" style="width: 50px; height: 50px;"></td>
                                                    <td>
                                                        <a href="edit_stock.php?id=<?php echo $stock['stock_id']; ?>"
                                                            type="button" class="btn btn-warning text-white">
                                                            <i class="far fa-edit"></i> แก้ไข
                                                        </a>
                                                        <button type='button' class='btn btn-danger delete-btn'
                                                            data-id='<?php echo $stock['stock_id']; ?>'>
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