<?php
$title = 'จัดการผู้ใช้ | Mira ศูนย์ความงามครบวงจร';
?>

<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i>
            จัดการผู้ใช้
        </h4>
        <a href="/user/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="managerTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="10%">รูปโปรไฟล์</th>
                    <th width="15%">ชื่อ-นามสกุล</th>
                    <th width="15%">ข้อมูลติดต่อ</th>
                    <th width="15%">สิทธิ์การใช้งาน</th>
                    <th width="10%">สถานะ</th>
                    <th width="15%">เข้าใช้งานล่าสุด</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $index + 1 ?></td>
                        <td class="text-center align-middle">
                            <img src="<?= !empty($user['image']) && $user['image'] ? '/assets/uploads/user/' . $user['image'] : '/assets/images/avatar.png' ?>"
                                alt="Profile"
                                class="img-circle"
                                style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">
                                    <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                                </span>
                                <small class="text-muted">
                                    <?= htmlspecialchars($user['username']) ?>
                                </small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span><i class="fas fa-envelope mr-2"></i><?= $user['email'] ? htmlspecialchars($user['email']) : 'ไม่มีข้อมูลอีเมล' ?></span>
                                <span><i class="fas fa-phone mr-2"></i><?= $user['phone'] ? htmlspecialchars($user['phone']) : 'ไม่มีข้อมูลเบอร์โทรศัพท์' ?></span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?php if (isset($user['role_name'])): ?>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($user['role_name']) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <?php if ($user['is_active'] == 1): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <?php if (!empty($user['last_login'])): ?>
                                <?= $this->dateFormat($user['last_login']); ?>
                            <?php else: ?>
                                <span class="text-muted">ไม่เคยเข้าใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                            <a href="/user/edit/<?php echo $user['user_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/user/delete/<?php echo $user['user_id']; ?>" method="POST" class="d-inline">
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
        $('#managerTable').DataTable({
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