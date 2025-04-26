<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-calendar-check"></i>
            จัดการข้อมูลการจอง
        </h4>
        <a href="/booking/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">บริการ</th>
                    <th width="15%">ลูกค้า</th>
                    <th width="12%">ผู้ให้บริการ</th>
                    <th width="10%">วันที่และเวลา</th>
                    <th width="8%">ราคา</th>
                    <th width="8%">โปรโมชั่น</th>
                    <th width="8%">สถานะ</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td class=""><?= $index + 1 ?></td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <div class="mb-2">
                                    <img src="<?= !empty($booking['service']['image']) ? '/assets/uploads/service/' . $booking['service']['image'] : '/assets/images/no-image.jpg' ?>"
                                        alt="Service"
                                        class="img-thumbnail"
                                        style="width: 80px; height: auto; object-fit: cover;">
                                </div>
                                <span><b><?= htmlspecialchars($booking['service']['name']) ?></b></span>
                                <small class="text-muted">ประเภท: <?= htmlspecialchars($booking['service']['type']['name']) ?></small>
                                <small class="text-muted">เวลา: <?= htmlspecialchars($booking['service']['time']) ?> นาที</small>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <span><?= htmlspecialchars($booking['customer']['firstname'] . ' ' . $booking['customer']['lastname']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($booking['customer']['email']) ?></small>
                                <small class="text-muted"><?= htmlspecialchars($booking['customer']['phone']) ?></small>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <?php if (!empty($booking['user'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= !empty($booking['user']['image']) ? '/assets/uploads/user/' . $booking['user']['image'] : '/assets/images/no-image.jpg' ?>"
                                            alt="User"
                                            class="img-thumbnail rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <span><?= htmlspecialchars($booking['user']['firstname'] . ' ' . $booking['user']['lastname']) ?></span>
                                    <small class="text-muted"><?= htmlspecialchars($booking['user']['role']['name']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">ยังไม่ได้กำหนด</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="">
                            <?= $this->dateFormat($booking['appointment_datetime']) ?>
                        </td>
                        <td class="">
                            <?= number_format($booking['total_price'], 2) ?> บาท
                        </td>
                        <td class="">
                            <?php if (!empty($booking['promotion']['name'])): ?>
                                <div class="d-flex flex-column">
                                    <span class="badge badge-success"><?= htmlspecialchars($booking['promotion']['name']) ?></span>
                                    <small class="text-danger">ส่วนลด <?= htmlspecialchars($booking['promotion']['discount']) ?></small>
                                    <?php if (!empty($booking['promotion']['code'])): ?>
                                        <small class="text-muted">รหัส: <?= htmlspecialchars($booking['promotion']['code']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">ไม่มี</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <?php if ($booking['status'] == 'pending'): ?>
                                <span class="badge badge-warning">รอยืนยัน</span>
                            <?php elseif ($booking['status'] == 'confirm'): ?>
                                <span class="badge badge-primary">ยืนยันแล้ว</span>
                            <?php elseif ($booking['status'] == 'cancel'): ?>
                                <span class="badge badge-danger">ยกเลิก</span>
                            <?php elseif ($booking['status'] == 'complete'): ?>
                                <span class="badge badge-success">เสร็จสิ้น</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <a href="/booking/edit/<?= $booking['booking_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/booking/delete/<?= $booking['booking_id']; ?>" method="POST" class="d-inline mt-1">
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
                [4, 'asc'] // Order by appointment date
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