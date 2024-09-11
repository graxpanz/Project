<?php
require_once('../authen.php');
function getStocksByServiceType($serviceTypeId) {
    global $conn;
    $sql = "SELECT stock_id, stock_name FROM stock WHERE service_type_id = :serviceTypeId";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['serviceTypeId' => $serviceTypeId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceTypes() {
    global $conn;
    $sql = "SELECT service_type_id, service_type_name FROM service_type";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['service_type_id'])) {
    echo json_encode(getStocksByServiceType($_GET['service_type_id']));
    exit;
}

$serviceTypes = getServiceTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน</title>
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
                                        เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
                                    </h4>
                                    <a href="./" class="btn btn-info mt-3">
                                        <i class="fas fa-list"></i>
                                        กลับหน้าหลัก
                                    </a>
                                </div>
                                <div class="card-body">
                                    <form id="stockForm" action="update_manage_add_stock.php" method="POST">
                                        <div id="stock-entries">
                                            <!-- Stock entries will be dynamically added here -->
                                        </div>
                                        <button type="button" class="btn btn-success add-entry mt-3">เพิ่มรายการ</button>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
                                        </div>
                                    </form>
                                </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            const stockEntriesContainer = document.getElementById('stock-entries');
            const addEntryButton = document.querySelector('.add-entry');
            const serviceTypes = <?php echo json_encode($serviceTypes); ?>;

            function createStockEntry() {
                const newEntry = document.createElement('div');
                newEntry.classList.add('stock-entry', 'mb-3');
                newEntry.innerHTML = `
                    <label for="service_type_id" class="form-label">เลือกประเภทสินค้า</label>
                    <select class="form-control service-type" name="service_type_id[]" required>
                        <option value="">กรุณาเลือกประเภทสินค้า</option>
                        ${serviceTypes.map(type => `<option value="${type.service_type_id}">${type.service_type_name}</option>`).join('')}
                    </select>
                    <label for="stock_id" class="form-label">เลือกสินค้า</label>
                    <select class="form-control stock-id" name="stock_id[]" required>
                        <option value="">กรุณาเลือกสินค้า</option>
                    </select>
                    <label for="amount" class="form-label">จำนวนที่ต้องการเพิ่ม</label>
                    <input type="number" class="form-control" name="amount[]" required>
                    <button type="button" class="btn btn-danger remove-entry mt-2">ลบรายการ</button>
                `;
                stockEntriesContainer.appendChild(newEntry);
            }

            addEntryButton.addEventListener('click', createStockEntry);

            stockEntriesContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-entry')) {
                    e.target.closest('.stock-entry').remove();
                }
            });

            stockEntriesContainer.addEventListener('change', function (e) {
                if (e.target.classList.contains('service-type')) {
                    const selectedServiceTypeId = e.target.value;
                    const stockIdSelect = e.target.parentElement.querySelector('.stock-id');

                    stockIdSelect.innerHTML = '<option value="">กรุณาเลือกสินค้า</option>';

                    if (selectedServiceTypeId) {
                        fetch(`${window.location.pathname}?service_type_id=${selectedServiceTypeId}`)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(stock => {
                                    const option = document.createElement('option');
                                    option.value = stock.stock_id;
                                    option.textContent = stock.stock_name;
                                    stockIdSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }
            });

            // Initialize with one stock entry
            createStockEntry();
        });
    </script>
</body>
</html>