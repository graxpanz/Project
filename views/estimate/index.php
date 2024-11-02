<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-circle"></i>
            การประเมินใบหน้า
        </h4>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="estimateTable" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="5%">ลำดับ</th>
                        <th>ชื่อลูกค้า</th>
                        <th class="text-center">รูปที่ให้ประเมิน</th>
                        <th class="text-center">วันที่ส่งให้ประเมิน</th>
                        <th class="text-end">ประเมินราคา</th>
                        <th class="text-center">สถานะ</th>
                        <th class="text-center" width="15%">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estimates as $index => $estimate): ?>
                        <tr>
                            <td class="text-center"><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($estimate['name']); ?></td>
                            <td class="text-center">
                                <?php if (!empty($estimate['file'])): ?>
                                    <img src="/uploads/estimates/<?php echo htmlspecialchars($estimate['file']); ?>"
                                        alt="Estimate"
                                        class="img-thumbnail"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted">ไม่มีรูป</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php echo date('d/m/Y', strtotime($estimate['date'])); ?>
                            </td>
                            <td class="text-end">
                                <?php
                                if (!empty($estimate['price'])) {
                                    echo number_format($estimate['price'], 2) . ' บาท';
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($estimate['status'] == 'Completed'): ?>
                                    <span class="badge bg-success">ตอบกลับแล้ว</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">ยังไม่ได้ตอบกลับ</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/estimate/detail/<?php echo $estimate['estimate_id']; ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="far fa-edit me-1"></i>
                                    รายละเอียด
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        // DataTable Configuration
        $('#estimateTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [
                [3, 'desc']
            ], // Sort by date
            "pageLength": 10,
            "language": {
                "lengthMenu": "แสดงข้อมูล _MENU_ รายการ",
                "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "search": "ค้นหา:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });

        // Form Validation and Submission
        $('#formData').on('submit', function(e) {
            e.preventDefault();

            // Simple validation
            if (!$('#response').val().trim()) {
                Swal.fire({
                    title: 'คำเตือน',
                    text: 'กรุณากรอกการตอบกลับ',
                    icon: 'warning'
                });
                return false;
            }

            if (!$('#price').val().trim()) {
                Swal.fire({
                    title: 'คำเตือน',
                    text: 'กรุณากรอกราคาที่ประเมิน',
                    icon: 'warning'
                });
                return false;
            }

            // Submit form using AJAX
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json'
            }).done(function(response) {
                Swal.fire({
                    text: 'บันทึกข้อมูลเรียบร้อย',
                    icon: 'success',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    window.location.href = '/estimate';
                });
            }).fail(function(xhr) {
                Swal.fire({
                    text: 'เกิดข้อผิดพลาดในการบันทึกข้อมูล',
                    icon: 'error',
                    confirmButtonText: 'ตกลง',
                });
            });
        });
    });
</script>