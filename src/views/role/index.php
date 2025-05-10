<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-shield"></i>
            จัดการสิทธ์ผู้ใช้
        </h4>
        <a href="/role/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
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
                        <td class=""><?= $index + 1; ?></td>
                        <td class=""><?= htmlspecialchars($role['name']); ?></td>
                        <td class=""><?php $this->showPermission($role['permission']) ?></td>
                        <td class="">
                            <?php if ($role['is_active']) { ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php } ?>
                        </td>
                        <td class=""><?= $this->dateFormat($role['updated_at']); ?></td>
                        <td class="">
                            <a href="/role/edit/<?= $role['user_role_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/role/delete/<?= $role['user_role_id']; ?>" method="POST" class="d-inline">
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
        $('#myTable').DataTable({
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