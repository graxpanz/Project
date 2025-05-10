<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-chart-bar"></i>
            รายงานรายรับ-รายจ่าย
        </h4>
        <a href="/finance" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <!-- ตัวกรองวันที่ -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/finance/report" method="GET" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">วันที่เริ่มต้น:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">วันที่สิ้นสุด:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> ค้นหา
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- สรุปยอดรวมตามช่วงเวลาที่เลือก -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    รายรับรวม</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($summary['total_income'] ?? 0, 2) ?> บาท</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-plus-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-danger shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    รายจ่ายรวม</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($summary['total_outcome'] ?? 0, 2) ?> บาท</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-minus-circle fa-2x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    คงเหลือ</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($summary['net_balance'] ?? 0, 2) ?> บาท</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- กราฟสรุปรายเดือน -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">สรุปรายรับ-รายจ่ายรายเดือน ปี <?= $current_year ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ตารางรายการ -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">รายการรายรับ-รายจ่าย</h6>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">ลำดับ</th>
                                    <th width="15%">วันที่</th>
                                    <th width="35%">รายละเอียด</th>
                                    <th width="15%">รายรับ</th>
                                    <th width="15%">รายจ่าย</th>
                                    <th width="15%">คงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalIncome = 0;
                                $totalOutcome = 0;
                                $balance = 0;
                                ?>
                                <?php foreach ($finances as $index => $finance): ?>
                                    <?php 
                                    $totalIncome += $finance['income'];
                                    $totalOutcome += $finance['outcome'];
                                    $balance = $totalIncome - $totalOutcome;
                                    ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $this->dateFormat($finance['transaction_date']) ?></td>
                                        <td><?= htmlspecialchars($finance['description']) ?></td>
                                        <td class="text-success font-weight-bold">
                                            <?= $finance['income'] > 0 ? number_format($finance['income'], 2) . ' บาท' : '-' ?>
                                        </td>
                                        <td class="text-danger font-weight-bold">
                                            <?= $finance['outcome'] > 0 ? number_format($finance['outcome'], 2) . ' บาท' : '-' ?>
                                        </td>
                                        <td class="font-weight-bold <?= $balance >= 0 ? 'text-primary' : 'text-danger' ?>">
                                            <?= number_format($balance, 2) ?> บาท
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($finances)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">ไม่พบรายการในช่วงเวลาที่เลือก</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th colspan="3" class="text-right">รวมทั้งหมด:</th>
                                    <th class="text-success"><?= number_format($totalIncome, 2) ?> บาท</th>
                                    <th class="text-danger"><?= number_format($totalOutcome, 2) ?> บาท</th>
                                    <th class="<?= $balance >= 0 ? 'text-primary' : 'text-danger' ?>"><?= number_format($balance, 2) ?> บาท</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function() {
        // Initialize DataTable
        $('#myTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [
                [1, 'desc']
            ],
            'language': {
                'lengthMenu': 'แสดงข้อมูล _MENU_ แถว',
                'zeroRecords': 'ไม่พบข้อมูลที่ต้องการ',
                'info': 'แสดงหน้า _PAGE_ จาก _PAGES_',
                'infoEmpty': 'ไม่พบข้อมูลที่ต้องการ',
                'infoFiltered': '(กรองจากทั้งหมด _MAX_ รายการ)',
                'search': 'ค้นหา:',
                'paginate': {
                    'first': 'หน้าแรก',
                    'last': 'หน้าสุดท้าย',
                    'next': 'ถัดไป',
                    'previous': 'ก่อนหน้า'
                }
            },
            'dom': 'Bfrtip',
            'buttons': [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // กราฟสรุปรายเดือน
        const monthlyData = <?= json_encode($monthly_summary) ?>;
        const months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        
        // เตรียมข้อมูลสำหรับกราฟ
        const chartLabels = [];
        const incomeData = [];
        const outcomeData = [];
        const balanceData = [];
        
        // จัดเรียงข้อมูลตามเดือน
        monthlyData.forEach(item => {
            chartLabels.push(months[item.month - 1]);
            incomeData.push(parseFloat(item.total_income));
            outcomeData.push(parseFloat(item.total_outcome));
            balanceData.push(parseFloat(item.net_balance));
        });
        
        // สร้างกราฟ
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'รายรับ',
                        data: incomeData,
                        backgroundColor: 'rgba(40, 167, 69, 0.5)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'รายจ่าย',
                        data: outcomeData,
                        backgroundColor: 'rgba(220, 53, 69, 0.5)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'คงเหลือ',
                        data: balanceData,
                        type: 'line',
                        fill: false,
                        borderColor: 'rgba(0, 123, 255, 1)',
                        tension: 0.1,
                        pointBackgroundColor: 'rgba(0, 123, 255, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'จำนวนเงิน (บาท)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'เดือน'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' บาท';
                            }
                        }
                    }
                }
            }
        });
    });
</script>