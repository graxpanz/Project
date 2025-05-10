<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-money-bill-wave"></i>
            จัดการรายรับ-รายจ่าย
        </h4>
        <div class="mt-3">
            <a href="/finance/add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                เพิ่มรายการ
            </a>
            <a href="/finance/report" class="btn btn-info ml-2">
                <i class="fas fa-chart-bar"></i>
                รายงาน
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- สรุปยอดรวม -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    รายรับทั้งหมด</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_income, 2) ?> บาท</div>
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
                                    รายจ่ายทั้งหมด</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_outcome, 2) ?> บาท</div>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($balance, 2) ?> บาท</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ตารางรายการ -->
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">วันที่</th>
                    <th width="35%">รายละเอียด</th>
                    <th width="15%">รายรับ</th>
                    <th width="15%">รายจ่าย</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($finances as $index => $finance): ?>
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
                        <td>
                            <a href="/finance/edit/<?= $finance['finance_id'] ?>" class="btn btn-warning text-white btn-sm">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/finance/delete/<?= $finance['finance_id'] ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger btn-sm delete-btn">
                                    <i class="far fa-trash-alt"></i> ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($finances)): ?>
                    <tr>
                        <td colspan="6" class="text-center">ไม่พบรายการ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

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
            }
        });

        // Delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'การดำเนินการนี้ไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, ลบข้อมูล!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>