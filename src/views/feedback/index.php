<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-comment"></i>
            จัดการข้อมูลความคิดเห็น
        </h4>
        <a href="/feedback/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">ชื่อลูกค้า</th>
                    <th width="15%">บริการ</th>
                    <th width="15%">คะแนน</th>
                    <th width="25%">ความคิดเห็น</th>
                    <th width="10%">สถานะ</th>
                    <th width="15%">วันที่</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedback as $index => $item): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $index + 1 ?></td>
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">
                                    <?= htmlspecialchars($item['customer_firstname'] . ' ' . $item['customer_lastname']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?php if (!empty($item['service_name'])): ?>
                                <span class="badge badge-info">
                                    <?= htmlspecialchars($item['service_name']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">ไม่ระบุบริการ</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <div class="star-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $item['rating']): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-muted"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ml-2">(<?= $item['rating'] ?>)</span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?php if (!empty($item['comment'])): ?>
                                <?= strlen($item['comment']) > 100 ? htmlspecialchars(substr($item['comment'], 0, 100)) . '...' : htmlspecialchars($item['comment']) ?>
                                <?php if (strlen($item['comment']) > 100): ?>
                                    <a href="#" class="show-full-comment" data-toggle="modal" data-target="#commentModal" data-comment="<?= htmlspecialchars($item['comment']) ?>">
                                        อ่านเพิ่มเติม
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">ไม่มีความคิดเห็น</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <?php if ($item['is_active'] == '1'): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <?= $this->dateFormat($item['created_at']); ?>
                        </td>
                        <td class="text-center align-middle">
                            <a href="/feedback/edit/<?php echo $item['feedback_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/feedback/delete/<?php echo $item['feedback_id']; ?>" method="POST" class="d-inline">
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

<!-- Modal สำหรับแสดงความคิดเห็นเต็ม -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">ความคิดเห็น</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="fullComment"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
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

        // แสดงความคิดเห็นเต็มใน Modal
        $('.show-full-comment').on('click', function() {
            const comment = $(this).data('comment');
            $('#fullComment').text(comment);
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