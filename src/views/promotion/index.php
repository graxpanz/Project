<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-tag"></i>
            จัดการข้อมูลโปรโมชั่น
        </h4>
        <a href="/promotion/add" class="btn btn-primary mt-3">
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
                    <th width="15%">ชื่อโปรโมชั่น</th>
                    <th width="20%">รายละเอียด</th>
                    <th width="10%">โค้ดส่วนลด</th>
                    <th width="10%">สถานะ</th>
                    <th width="15%">อัพเดทล่าสุด</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promotions as $index => $promotion): ?>
                    <tr>
                        <td class=""><?= $index + 1 ?></td>
                        <td class="">
                            <img src="<?= !empty($promotion['image']) && $promotion['image'] ? '/assets/uploads/promotion/' . $promotion['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Service"
                                class="img-thumbnail"
                                style="width: 120px; height: auto; object-fit: cover;">
                        </td>
                        <td class="">
                            <?= htmlspecialchars($promotion['name']) ?>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <span>วันที่เริ่ม: <?= $this->dateFormat($promotion['start_datetime']) ?></span>
                                <span>วันที่สิ้นสุด: <?= $this->dateFormat($promotion['end_datetime']) ?></span>
                                <span>รายละเอียด: <?= htmlspecialchars($promotion['description']) ?></span>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <span>โค้ด: <?= htmlspecialchars($promotion['code']) ?></span>
                                <span>ส่วนลด: <?= htmlspecialchars($promotion['discount']) ?> บาท</span>
                            </div>
                        </td>
                        <td class="">
                            <?php if ($promotion['is_active'] == 1): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class=""><?= $this->dateFormat($promotion['updated_at']); ?></td>
                        <td class="">
                            <a href="/promotion/edit/<?php echo $promotion['promotion_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/promotion/delete/<?php echo $promotion['promotion_id']; ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger delete-btn">
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

        // Toggle status
        $('.toggle-status').on('click', function() {
            const id = $(this).data('id');
            const newStatus = $(this).data('status');
            const statusText = newStatus === 1 ? 'เปิด' : 'ปิด';

            Swal.fire({
                title: 'ยืนยันการเปลี่ยนแปลง?',
                text: `คุณต้องการ${statusText}การใช้งานผู้ใช้งานนี้หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, เปลี่ยนแปลง!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ส่ง request ไปยัง endpoint สำหรับเปลี่ยนสถานะ
                    $.post(`/user/toggle-status/${id}`, {
                            status: newStatus
                        })
                        .done(function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'เปลี่ยนแปลงสถานะเรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถเปลี่ยนแปลงสถานะได้'
                            });
                        });
                }
            });
        });
    });
</script>