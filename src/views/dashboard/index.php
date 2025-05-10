<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">แดชบอร์ด</h1>
        <div class="d-flex align-items-center">
            <form action="/dashboard" method="GET" class="form-inline mr-2">
                <div class="input-group input-group-sm mr-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text">จาก</span>
                    </div>
                    <input type="date" class="form-control" name="start_date" value="<?= $start_date ?>">
                </div>
                <div class="input-group input-group-sm mr-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text">ถึง</span>
                    </div>
                    <input type="date" class="form-control" name="end_date" value="<?= $end_date ?>">
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter fa-sm"></i>
                </button>
            </form>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="printReport">
                <i class="fas fa-download fa-sm text-white-50"></i> พิมพ์รายงาน
            </a>
        </div>
    </div>

    <!-- Content Row - Summary Cards -->
    <div class="row">
        <!-- ลูกค้าทั้งหมด -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                ลูกค้าทั้งหมด</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_customers) ?> คน</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- รายการนัดหมาย -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                นัดหมายในช่วงเวลา</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_bookings) ?> ครั้ง</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- รายรับ -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                รายรับรวม</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($revenue_summary['total_income'] ?? 0, 2) ?> บาท</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- กำไรสุทธิ -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                กำไรสุทธิ</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($revenue_summary['net_balance'] ?? 0, 2) ?> บาท</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Main Charts -->
    <div class="row">
        <!-- Area Chart - Monthly Revenue -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">รายรับรายจ่ายรายเดือน (<?= $current_year ?>)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyFinanceChart" style="min-height: 370px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart - Booking Status -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">สถานะการนัดหมาย</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="bookingStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-warning"></i> รอยืนยัน (<?= $booking_status_stats['pending'] ?>)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> ยืนยันแล้ว (<?= $booking_status_stats['confirm'] ?>)
                        </span>
                        <br class="d-md-none">
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger"></i> ยกเลิก (<?= $booking_status_stats['cancel'] ?>)
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> เสร็จสิ้น (<?= $booking_status_stats['complete'] ?>)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Today's Bookings and New Customers -->
    <div class="row">
        <!-- Today's Bookings -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">การนัดหมายวันนี้</h6>
                    <a href="/booking" class="btn btn-sm btn-primary ml-auto">
                        <i class="fas fa-calendar"></i> ดูทั้งหมด
                    </a>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    <?php if (empty($todays_bookings)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-day fa-3x text-gray-300 mb-3"></i>
                            <p>ไม่มีการนัดหมายในวันนี้</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($todays_bookings as $booking): ?>
                            <div class="border-left-<?= getStatusColor($booking['status']) ?> shadow-sm p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5 class="mb-0"><?= htmlspecialchars($booking['customer_firstname'] . ' ' . $booking['customer_lastname']) ?></h5>
                                            <span class="badge badge-<?= getStatusColor($booking['status']) ?> ml-2"><?= getStatusText($booking['status']) ?></span>
                                        </div>
                                        <p class="mb-1"><strong>บริการ:</strong> <?= htmlspecialchars($booking['service_name']) ?></p>
                                        <p class="mb-0"><strong>ติดต่อ:</strong> <?= htmlspecialchars($booking['customer_phone'] ?? '-') ?></p>
                                    </div>
                                    <div class="col-md-5 text-md-right">
                                        <h4 class="text-primary"><?= date('H:i', strtotime($booking['appointment_datetime'])) ?></h4>
                                        <p class="mb-1"><?= $booking['service_time'] ?> นาที</p>
                                        <a href="/booking/edit/<?= $booking['booking_id'] ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> ดูรายละเอียด
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- New Customers Monthly -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">ลูกค้าใหม่รายเดือน</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="newCustomersChart" style="min-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Popular Services and Top Employees -->
    <div class="row">
        <!-- Popular Services -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">บริการยอดนิยม</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="popularServicesChart" style="min-height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Employees -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">พนักงานยอดเยี่ยม</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($top_employees)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-tie fa-3x text-gray-300 mb-3"></i>
                            <p>ไม่มีข้อมูลพนักงาน</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>พนักงาน</th>
                                        <th class="text-center">จำนวนนัดหมาย</th>
                                        <th class="text-center">รายได้รวม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_employees as $index => $employee): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle mr-2 bg-<?= getEmployeeColor($index) ?>">
                                                        <?= strtoupper(substr($employee['firstname'], 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <?= htmlspecialchars($employee['firstname'] . ' ' . $employee['lastname']) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><?= number_format($employee['booking_count']) ?></td>
                                            <td class="text-center font-weight-bold"><?= number_format($employee['total_revenue'], 2) ?> บาท</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row - Low Stock and Feedback -->
    <div class="row">
        <!-- Low Stock Supplies -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">วัสดุสิ้นเปลืองที่ใกล้หมด</h6>
                    <a href="/supply/low-stock" class="btn btn-sm btn-primary ml-auto">
                        <i class="fas fa-boxes"></i> ดูทั้งหมด
                    </a>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <?php if (empty($low_stock_supplies)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p>ไม่มีวัสดุสิ้นเปลืองที่ใกล้หมด</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($low_stock_supplies as $supply): ?>
                                <a href="/supply/view/<?= $supply['supply_id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= htmlspecialchars($supply['name']) ?></h6>
                                        <span class="badge badge-danger">เหลือ <?= $supply['current_stock'] ?> <?= htmlspecialchars($supply['unit']) ?></span>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="text-muted">ขั้นต่ำ: <?= $supply['min_quantity'] ?> <?= htmlspecialchars($supply['unit']) ?></small>
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            ควรสั่งเพิ่ม <?= $supply['min_quantity'] - $supply['current_stock'] ?> <?= htmlspecialchars($supply['unit']) ?>
                                        </small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="/stock/stock-in" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> รับเข้าสต็อก
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Customer Feedback -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        ความพึงพอใจของลูกค้า
                        <span class="badge badge-<?= getRatingColor($feedback_stats['average']) ?> ml-2">
                            <?= $feedback_stats['average'] ?> <i class="fas fa-star"></i>
                        </span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="rating-bars mb-4">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <div class="rating-bar d-flex align-items-center mb-2">
                                <div class="rating-label mr-2">
                                    <?= $i ?> <i class="fas fa-star text-warning"></i>
                                </div>
                                <div class="progress flex-grow-1" style="height: 12px;">
                                    <?php 
                                    $percentage = $feedback_stats['total'] > 0 ? ($feedback_stats[$i] / $feedback_stats['total']) * 100 : 0;
                                    ?>
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percentage ?>%;"
                                         aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <div class="rating-count ml-2">
                                    <?= $feedback_stats[$i] ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <hr>
                    
                    <h6 class="font-weight-bold mb-3">ความคิดเห็นล่าสุด</h6>
                    
                    <?php if (empty($recent_feedback)): ?>
                        <div class="text-center py-3">
                            <p class="text-muted">ยังไม่มีความคิดเห็น</p>
                        </div>
                    <?php else: ?>
                        <div class="testimonials">
                            <?php foreach ($recent_feedback as $feedback): ?>
                                <div class="testimonial mb-3 p-3 border-left-warning shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="font-weight-bold"><?= htmlspecialchars($feedback['customer_firstname'] . ' ' . $feedback['customer_lastname']) ?></div>
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $feedback['rating'] ? 'text-warning' : 'text-gray-300' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        บริการ: <?= htmlspecialchars($feedback['service_name'] ?? 'ทั่วไป') ?> | 
                                        <?= date('d/m/Y', strtotime($feedback['created_at'])) ?>
                                    </div>
                                    <div class="testimonial-text">
                                        <?= !empty($feedback['comment']) ? htmlspecialchars($feedback['comment']) : '<span class="text-muted">ไม่มีความคิดเห็น</span>' ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
// Helper functions
function getStatusText($status) {
    switch ($status) {
        case 'pending': return 'รอยืนยัน';
        case 'confirm': return 'ยืนยันแล้ว';
        case 'cancel': return 'ยกเลิก';
        case 'complete': return 'เสร็จสิ้น';
        default: return $status;
    }
}

function getStatusColor($status) {
    switch ($status) {
        case 'pending': return 'warning';
        case 'confirm': return 'success';
        case 'cancel': return 'danger';
        case 'complete': return 'primary';
        default: return 'secondary';
    }
}

function getEmployeeColor($index) {
    $colors = ['primary', 'success', 'info', 'warning', 'danger'];
    return $colors[$index % count($colors)];
}

function getRatingColor($rating) {
    if ($rating >= 4.5) return 'success';
    if ($rating >= 3.5) return 'info';
    if ($rating >= 2.5) return 'warning';
    return 'danger';
}
?>

<!-- Page level styles -->
<style>
.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: white;
    text-align: center;
    line-height: 30px;
    font-weight: bold;
}

.rating-label {
    width: 40px;
    text-align: right;
}

.rating-count {
    width: 30px;
    text-align: right;
}

@media print {
    .sidebar, .topbar, form, .btn, .no-print {
        display: none !important;
    }
    
    .content-wrapper, .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        break-inside: avoid;
    }
}
</style>

<!-- Page level scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ข้อมูลกราฟ
    const monthlyFinanceData = <?= json_encode($monthly_finances) ?>;
    const bookingStatusData = <?= json_encode($booking_status_stats) ?>;
    const popularServicesData = <?= json_encode($popular_services) ?>;
    const newCustomersData = <?= json_encode($new_customers_monthly) ?>;
    
    // กราฟรายรับ-รายจ่ายรายเดือน
    const monthNames = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    
    const monthlyFinanceCtx = document.getElementById('monthlyFinanceChart').getContext('2d');
    new Chart(monthlyFinanceCtx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [
                {
                    label: 'รายรับ',
                    data: monthlyFinanceData.map(item => item.total_income),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'รายจ่าย',
                    data: monthlyFinanceData.map(item => item.total_outcome),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'กำไรสุทธิ',
                    data: monthlyFinanceData.map(item => item.net_balance),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + Number(context.raw).toLocaleString() + ' บาท';
                        }
                    }
                },
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' บาท';
                        }
                    }
                }
            }
        }
    });
    
    // กราฟสถานะการนัดหมาย
    const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
    new Chart(bookingStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['รอยืนยัน', 'ยืนยันแล้ว', 'ยกเลิก', 'เสร็จสิ้น'],
            datasets: [{
                data: [
                    bookingStatusData.pending, 
                    bookingStatusData.confirm, 
                    bookingStatusData.cancel, 
                    bookingStatusData.complete
                ],
                backgroundColor: [
                    '#f6c23e', // warning
                    '#1cc88a', // success
                    '#e74a3b', // danger
                    '#4e73df'  // primary
                ],
                hoverBackgroundColor: [
                    '#e0b036',
                    '#17a673',
                    '#d43c2d',
                    '#3b5fd2'
                ],
                borderWidth: 0,
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            cutout: '70%'
        }
    });
    
    // กราฟบริการยอดนิยม
    const popularServicesCtx = document.getElementById('popularServicesChart').getContext('2d');
    new Chart(popularServicesCtx, {
        type: 'bar',
        data: {
            labels: popularServicesData.map(service => service.name),
            datasets: [{
                label: 'จำนวนการนัดหมาย',
                data: popularServicesData.map(service => service.booking_count),
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // กราฟลูกค้าใหม่รายเดือน
    const newCustomersCtx = document.getElementById('newCustomersChart').getContext('2d');
    new Chart(newCustomersCtx, {
        type: 'bar',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'ลูกค้าใหม่',
                data: Object.values(newCustomersData),
                backgroundColor: 'rgba(28, 200, 138, 0.8)',
                borderColor: 'rgba(28, 200, 138, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // พิมพ์รายงาน
    document.getElementById('printReport').addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
});
</script>