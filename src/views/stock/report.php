<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-chart-bar"></i>
            รายงานการเคลื่อนไหวสต็อก
        </h4>
        <a href="/stock" class="btn btn-secondary mt-3">
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
                        <form action="/stock/report" method="GET" class="form-inline">
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

        <!-- สรุปการเคลื่อนไหว -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">สรุปการเคลื่อนไหวสต็อก (<?= $this->dateFormat($start_date) ?> - <?= $this->dateFormat($end_date) ?>)</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        // คำนวณสรุปข้อมูล
                        $totalIn = 0;
                        $totalOut = 0;
                        $movementsBySupply = [];
                        
                        foreach ($movements as $movement) {
                            if ($movement['movement_type'] == 'in') {
                                $totalIn++;
                            } else {
                                $totalOut++;
                            }
                            
                            $supplyId = $movement['supply_id'];
                            if (!isset($movementsBySupply[$supplyId])) {
                                $movementsBySupply[$supplyId] = [
                                    'name' => $movement['supply_name'],
                                    'in' => 0,
                                    'out' => 0
                                ];
                            }
                            
                            if ($movement['movement_type'] == 'in') {
                                $movementsBySupply[$supplyId]['in'] += $movement['quantity'];
                            } else {
                                $movementsBySupply[$supplyId]['out'] += $movement['quantity'];
                            }
                        }
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    การรับเข้าสต็อก</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalIn ?> รายการ</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                    การเบิกออกสต็อก</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalOut ?> รายการ</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- กราฟแสดงการเคลื่อนไหวรายวัสดุ -->
                        <div class="mt-4">
                            <h5>การเคลื่อนไหวรายวัสดุสิ้นเปลือง</h5>
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="supplyMovementChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ตารางรายการทั้งหมด -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">รายการเคลื่อนไหวทั้งหมด</h6>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">ลำดับ</th>
                                    <th width="10%">วันที่</th>
                                    <th width="15%">วัสดุสิ้นเปลือง</th>
                                    <th width="10%">ประเภท</th>
                                    <th width="10%">จำนวน</th>
                                    <th width="20%">รายละเอียด</th>
                                    <th width="10%">อ้างอิง</th>
                                    <th width="20%">ผู้ที่เกี่ยวข้อง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movements as $index => $movement): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $this->dateFormat($movement['movement_date']) ?></td>
                                        <td><?= htmlspecialchars($movement['supply_name']) ?></td>
                                        <td>
                                            <?php if ($movement['movement_type'] == 'in'): ?>
                                                <span class="badge badge-success">รับเข้า</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">เบิกออก</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($movement['quantity']) ?> <?= htmlspecialchars($movement['unit']) ?></td>
                                        <td><?= !empty($movement['notes']) ? htmlspecialchars($movement['notes']) : '-' ?></td>
                                        <td><?= !empty($movement['reference']) ? htmlspecialchars($movement['reference']) : '-' ?></td>
                                        <td><?= $this->dateFormat($movement['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($movements)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">ไม่พบข้อมูลการเคลื่อนไหวในช่วงเวลาที่เลือก</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
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

        // สร้างกราฟแสดงการเคลื่อนไหวรายวัสดุ
        const movementsBySupply = <?= json_encode(array_values($movementsBySupply)) ?>;
        
        const ctx = document.getElementById('supplyMovementChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: movementsBySupply.map(item => item.name),
                datasets: [
                    {
                        label: 'รับเข้า',
                        data: movementsBySupply.map(item => item.in),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'เบิกออก',
                        data: movementsBySupply.map(item => item.out),
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
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
                            text: 'จำนวน'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'วัสดุสิ้นเปลือง'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    });
</script>