<?php
$title = 'จัดการผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร';
?>

<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i> 
            ผู้ดูแลระบบ
        </h4>
        <a href="/manager/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="managerTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อผู้ใช้งาน</th>
                    <th>ชื่อจริง</th>
                    <th>นามสกุล</th>
                    <th>สิทธิ์เข้าใช้งาน</th>
                    <th>ใช้งานล่าสุด</th>
                    <th>การเปลี่ยนแปลง</th>
                </tr>
            </thead>   
            <tbody>
                <?php foreach ($managers as $index => $manager): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($manager['username']); ?></td>
                        <td><?php echo htmlspecialchars($manager['firstname']); ?></td>
                        <td><?php echo htmlspecialchars($manager['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($manager['status']); ?></td>
                        <td><?php echo htmlspecialchars($manager['created_at']); ?></td>
                        <td>
                            <a href="/manager/edit/<?php echo $manager['u_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/manager/delete/<?php echo $manager['u_id']; ?>" method="POST" class="d-inline">
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
        $('#managerTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'language': {
                'lengthMenu': 'แสดงข้อมูล _MENU_ แถว',
                'zeroRecords': 'ไม่พบข้อมูลที่ต้องการ',
                'info': 'แสดงหน้า _PAGE_ จาก _PAGES_',
                'infoEmpty': 'ไม่พบข้อมูลที่ต้องการ',
                'infoFiltered': '(filtered from _MAX_ total records)',
                'search': 'ค้นหา'
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