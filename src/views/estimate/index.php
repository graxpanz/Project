<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-clipboard-list"></i>
            จัดการข้อมูลการประเมิน
        </h4>
        <a href="/estimate/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="10%">รูปภาพ</th>
                    <th width="15%">ลูกค้า</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="10%">สถานะ</th>
                    <th width="10%">การใช้งาน</th>
                    <th width="15%">อัพเดทล่าสุด</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estimates as $index => $estimate): ?>
                    <tr>
                        <td class=""><?= $index + 1 ?></td>
                        <td class="">
                            <img src="<?= !empty($estimate['image']) && $estimate['image'] ? '/assets/uploads/estimate/' . $estimate['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Estimate"
                                class="img-thumbnail"
                                style="width: 120px; height: auto; object-fit: cover;">
                        </td>
                        <td class="">
                            <?= htmlspecialchars($estimate['customer_name']) ?>
                            <div class="small text-muted">โทร: <?= htmlspecialchars($estimate['customer_phone']) ?></div>
                        </td>
                        <td class="">
                            <div><?= htmlspecialchars($estimate['description']) ?></div>
                            <?php if (!empty($estimate['response'])): ?>
                                <div class="mt-2 pt-2 border-top">
                                    <strong>การตอบกลับ:</strong> <?= htmlspecialchars($estimate['response']) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <?php if ($estimate['status'] == 'pending'): ?>
                                <span class="badge badge-warning">รอการตอบกลับ</span>
                            <?php else: ?>
                                <span class="badge badge-success">ตอบกลับแล้ว</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <?php if ($estimate['is_active'] == '1'): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class=""><?= $this->dateFormat($estimate['updated_at']); ?></td>
                        <td class="">
                            <?php if ($estimate['status'] == 'pending'): ?>
                                <a href="/estimate/respond/<?php echo $estimate['estimate_id']; ?>" class="btn btn-success text-white mb-1">
                                    <i class="far fa-comment"></i> ตอบกลับ
                                </a>
                            <?php endif; ?>
                            <a href="/estimate/edit/<?php echo $estimate['estimate_id']; ?>" class="btn btn-warning text-white mb-1">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/estimate/delete/<?php echo $estimate['estimate_id']; ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger delete-btn mb-1">
                                    <i class="far fa-trash-alt"></i> ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Initialize DataTable
        $('#myTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [
                [0, 'asc']
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