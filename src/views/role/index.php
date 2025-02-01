<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-tag"></i>
            จัดการสิทธ์การใช้งาน
        </h4>
        <a href="/role/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลสิทธ์การใช้งาน
        </a>
    </div>
    <div class="card-body">
        <table id="roleTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อตำแหน่ง</th>
                    <th>สิทธ์การเข้าถึง</th>
                    <th>สถานะ</th>
                    <th>อัพเดทล่าสุด</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $index => $role): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($role['name']); ?></td>
                        <td><?php $this->showPermission($role['permission']) ?></td>
                        <td>
                            <?php if ($role['is_active']) { ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php } ?>
                        </td>
                        <td><?php echo $this->dateFormat($role['updated_at']); ?></td>
                        <td>
                            <a href="/role/edit/<?php echo $role['role_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/role/delete/<?php echo $role['role_id']; ?>" method="POST" class="d-inline">
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
        $('#roleTable').DataTable({
            'responsive': true,
            'autoWidth': false,
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

        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณจะไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>