<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-boxes"></i>
            จัดการวัสดุสิ้นเปลือง
        </h4>
        <div class="mt-3">
            <a href="/supply/add" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                เพิ่มวัสดุสิ้นเปลือง
            </a>
            <a href="/supply/low-stock" class="btn btn-warning text-white ml-2">
                <i class="fas fa-exclamation-circle"></i>
                วัสดุใกล้หมด
            </a>
        </div>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="10%">รูปภาพ</th>
                    <th width="20%">ชื่อ</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="10%">หน่วย</th>
                    <th width="15%">จำนวนคงเหลือ</th>
                    <th width="10%">สถานะ</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($supplies as $index => $supply): ?>
                    <tr>
                        <td class=""><?= $index + 1 ?></td>
                        <td class="">
                            <img src="<?= !empty($supply['image']) ? '/assets/uploads/supplies/' . $supply['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Supply"
                                class="img-thumbnail"
                                style="width: 70px; height: 70px; object-fit: cover;">
                        </td>
                        <td class="">
                            <?= htmlspecialchars($supply['name']) ?>
                        </td>
                        <td class="">
                            <?= !empty($supply['description']) ? htmlspecialchars($supply['description']) : '-' ?>
                        </td>
                        <td class="">
                            <?= htmlspecialchars($supply['unit']) ?>
                        </td>
                        <td class="<?= $supply['current_stock'] <= $supply['min_quantity'] ? 'text-danger font-weight-bold' : '' ?>">
                            <?= number_format($supply['current_stock']) ?>
                            <?php if ($supply['current_stock'] <= $supply['min_quantity']): ?> <span class="badge badge-danger">ใกล้หมด</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <?php if ($supply['is_active'] == '1'): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <a href="/supply/edit/<?= $supply['supply_id'] ?>" class="btn btn-warning text-white btn-sm mb-1">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/supply/delete/<?= $supply['supply_id'] ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger btn-sm delete-btn mb-1">
                                    <i class="far fa-trash-alt"></i> ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($supplies)): ?>
                    <tr>
                        <td colspan="8" class="text-center">ไม่พบข้อมูลวัสดุสิ้นเปลือง</td>
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
                [2, 'asc']
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