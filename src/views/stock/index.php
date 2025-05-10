<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-warehouse"></i>
            จัดการคลังสินค้า
        </h4>
        <div class="mt-3">
            <a href="/stock/stock-in" class="btn btn-success">
                <i class="fas fa-plus-circle"></i>
                รับเข้าสต็อก
            </a>
            <a href="/stock/stock-out" class="btn btn-danger ml-2">
                <i class="fas fa-minus-circle"></i>
                เบิกออกสต็อก
            </a>
            <a href="/stock/report" class="btn btn-info ml-2">
                <i class="fas fa-chart-bar"></i>
                รายงาน
            </a>
            <a href="/supply" class="btn btn-primary ml-2">
                <i class="fas fa-boxes"></i>
                จัดการวัสดุสิ้นเปลือง
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">วันที่</th>
                    <th width="15%">วัสดุสิ้นเปลือง</th>
                    <th width="10%">ประเภท</th>
                    <th width="10%">จำนวน</th>
                    <th width="25%">รายละเอียด</th>
                    <th width="10%">อ้างอิง</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movements as $index => $movement): ?>
                    <tr>
                        <td class=""><?= $index + 1 ?></td>
                        <td class=""><?= $this->dateFormat($movement['movement_date']) ?></td>
                        <td class=""><?= htmlspecialchars($movement['supply_name']) ?></td>
                        <td class="">
                            <?php if ($movement['movement_type'] == 'in'): ?>
                                <span class="badge badge-success">รับเข้า</span>
                            <?php else: ?>
                                <span class="badge badge-danger">เบิกออก</span>
                            <?php endif; ?>
                        </td>
                        <td class=""><?= number_format($movement['quantity']) ?> <?= htmlspecialchars($movement['unit']) ?></td>
                        <td class="">
                            <?= !empty($movement['notes']) ? htmlspecialchars($movement['notes']) : '-' ?>
                        </td>
                        <td class="">
                            <?= !empty($movement['reference']) ? htmlspecialchars($movement['reference']) : '-' ?>
                        </td>
                        <td class="">
                            <a href="/stock/edit/<?= $movement['movement_id'] ?>" class="btn btn-warning text-white btn-sm mb-1">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/stock/delete/<?= $movement['movement_id'] ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger btn-sm delete-btn mb-1">
                                    <i class="far fa-trash-alt"></i> ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($movements)): ?>
                    <tr>
                        <td colspan="8" class="text-center">ไม่พบข้อมูลการเคลื่อนไหวสต็อก</td>
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