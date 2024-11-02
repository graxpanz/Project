<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-tag"></i>
            จัดการข้อมูลโปรโมชั่น
        </h4>
        <a href="/promotion/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลโปรโมชั่น
        </a>
    </div>
    <div class="card-body">
        <table id="promotionTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อโปรโมชั่น</th>
                    <th>รายละเอียด</th>
                    <th>วันที่เริ่มโปรโมชั่น</th>
                    <th>วันที่สิ้นสุดโปรโมชั่น</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($promotions as $index => $promotion): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($promotion['promotion_name']); ?></td>
                        <td><?php echo htmlspecialchars($promotion['detail']); ?></td>
                        <td><?php echo htmlspecialchars($promotion['date_start']); ?></td>
                        <td><?php echo htmlspecialchars($promotion['date_end']); ?></td>
                        <td>
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
        $('#promotionTable').DataTable({
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