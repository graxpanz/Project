<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-comments"></i>
            การแสดงความคิดเห็นของลูกค้า
        </h4>
    </div>
    <div class="card-body">
        <table id="commentTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ความคิดเห็นลูกค้า</th>
                    <th>การตอบกลับ</th>
                    <th>วันที่แสดงความคิดเห็น</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $index => $comment): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($comment['comment']); ?></td>
                        <td><?php echo htmlspecialchars($comment['response']); ?></td>
                        <td><?php echo htmlspecialchars($comment['date']); ?></td>
                        <td>
                            <a href="/comment/detail/<?php echo $comment['comment_id']; ?>" class="btn btn-info">
                                <i class="far fa-edit"></i> รายละเอียด
                            </a>
                            <form action="/comment/delete/<?php echo $comment['comment_id']; ?>" method="POST" class="d-inline">
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
        $('#commentTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [[3, 'desc']], // เรียงตามวันที่ล่าสุด
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