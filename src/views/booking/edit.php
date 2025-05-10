<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-calendar-check"></i>
            แก้ไขข้อมูลการจอง
        </h4>
        <a href="/booking" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/booking/update" method="POST">
        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="customer_id">ลูกค้า</label>
                        <select class="form-control select2" name="customer_id" id="customer_id" required>
                            <option value="" disabled>เลือกลูกค้า</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= ($booking['customer_id'] == $customer['customer_id']) ? 'selected' : '' ?>>
                                    <?= $customer['firstname'] . ' ' . $customer['lastname'] . ' (' . $customer['phone'] . ')' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service_id">บริการ</label>
                        <select class="form-control select2" name="service_id" id="service_id" required>
                            <option value="" disabled>เลือกบริการ</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['service_id'] ?>"
                                    data-price="<?= $service['price'] ?>"
                                    data-time="<?= $service['time'] ?>"
                                    data-image="<?= !empty($service['image']) ? '/assets/uploads/service/' . $service['image'] : '/assets/images/no-image.jpg' ?>"
                                    <?= ($booking['service_id'] == $service['service_id']) ? 'selected' : '' ?>>
                                    <?= $service['name'] ?> - <?= $service['price'] ?> บาท (<?= $service['time'] ?> นาที)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">วันที่นัดหมาย</label>
                        <input type="date" class="form-control" name="appointment_date" id="appointment_date" required
                            value="<?= date('Y-m-d', strtotime($booking['appointment_datetime'])) ?>">
                    </div>

                    <div class="form-group">
                        <label for="appointment_time">เวลานัดหมาย</label>
                        <input type="time" class="form-control" name="appointment_time" id="appointment_time" required
                            value="<?= date('H:i', strtotime($booking['appointment_datetime'])) ?>">
                    </div>

                    <div class="form-group">
                        <label for="user_id">ผู้ให้บริการ</label>
                        <select class="form-control select2" name="user_id" id="user_id">
                            <option value="">ไม่ระบุ (กำหนดภายหลัง)</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['user_id'] ?>" <?= ($booking['user_id'] == $employee['user_id']) ? 'selected' : '' ?>>
                                    <?= $employee['firstname'] . ' ' . $employee['lastname'] ?>
                                    (<?= $employee['role_name'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="promotion_id">โปรโมชั่น</label>
                        <select class="form-control select2" name="promotion_id" id="promotion_id">
                            <option value="">ไม่มีโปรโมชั่น</option>
                            <?php foreach ($promotions as $promotion): ?>
                                <option value="<?= $promotion['promotion_id'] ?>"
                                    data-discount="<?= $promotion['discount'] ?>"
                                    <?= ($booking['promotion_id'] == $promotion['promotion_id']) ? 'selected' : '' ?>>
                                    <?= $promotion['name'] ?> - ส่วนลด <?= $promotion['discount'] ?> บาท
                                    <?php if (!empty($promotion['code'])): ?>
                                        (รหัส: <?= $promotion['code'] ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">สถานะการจอง</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="pending" <?= ($booking['status'] == 'pending') ? 'selected' : '' ?>>รอยืนยัน</option>
                            <option value="confirm" <?= ($booking['status'] == 'confirm') ? 'selected' : '' ?>>ยืนยันแล้ว</option>
                            <option value="cancel" <?= ($booking['status'] == 'cancel') ? 'selected' : '' ?>>ยกเลิก</option>
                            <option value="complete" <?= ($booking['status'] == 'complete') ? 'selected' : '' ?>>เสร็จสิ้น</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="note">หมายเหตุ</label>
                        <textarea class="form-control" name="note" id="note" rows="3" placeholder="หมายเหตุเพิ่มเติม"><?= htmlspecialchars($booking['note'] ?? '') ?></textarea>
                    </div>

                    <div class="card border-info mt-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">สรุปการจอง</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <img id="service_image_preview"
                                        src="<?= !empty($booking['service']['image']) ? '/assets/uploads/service/' . $booking['service']['image'] : '/assets/images/no-image.jpg' ?>"
                                        alt="Service Preview"
                                        class="img-thumbnail"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">ราคาบริการ:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext" id="price_display" value="<?= number_format($booking['price'], 2) ?> บาท" readonly>
                                            <input type="hidden" name="price" id="price" value="<?= $booking['price'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">ส่วนลดโปรโมชั่น:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext text-danger" id="discount_display" value="<?= number_format($booking['discount'] ?? 0, 2) ?> บาท" readonly>
                                            <input type="hidden" name="discount" id="discount" value="<?= $booking['discount'] ?? 0 ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">มัดจำ (10%):</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext text-primary" id="deposit_price_display" value="<?= number_format($booking['deposit_price'] ?? 0, 2) ?> บาท" readonly>
                                            <input type="hidden" name="deposit_price" id="deposit_price" value="<?= $booking['deposit_price'] ?? 0 ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label font-weight-bold">ราคาสุทธิ:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext font-weight-bold text-success" id="total_price_display" value="<?= number_format($booking['total_price'] ?? 0, 2) ?> บาท" readonly>
                                            <input type="hidden" name="total_price" id="total_price" value="<?= $booking['total_price'] ?? 0 ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label font-weight-bold">หลักฐานการโอนเงินมัดจำ:</label>
                                        <div class="col-sm-6">
                                            <?php if (!empty($transactions) && is_array($transactions)): ?>
                                                <div class="transaction-images d-flex flex-wrap">
                                                    <?php foreach ($transactions as $index => $transaction): ?>
                                                        <?php if (!empty($transaction['tansaction_image']) || !empty($transaction['image'])): ?>
                                                            <?php $imageFile = !empty($transaction['tansaction_image']) ? $transaction['tansaction_image'] : $transaction['image']; ?>
                                                            <a href="javascript:void(0)"
                                                                class="view-transaction-image mr-1 mb-1"
                                                                data-toggle="modal"
                                                                data-target="#transactionImageModal"
                                                                data-image="/assets/uploads/transaction/<?= $imageFile ?>">
                                                                <img src="/assets/uploads/transaction/<?= $imageFile ?>"
                                                                    alt="หลักฐานการโอนเงิน"
                                                                    class="img-thumbnail"
                                                                    style="height: auto; width: 120px; object-fit: cover;">
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center">
                                                    <span class="text-muted mr-2">ไม่มีหลักฐาน</span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary"
                                                        data-toggle="modal"
                                                        data-target="#uploadTransactionModal">
                                                        <i class="fas fa-plus"></i> เพิ่ม
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>

<!-- Modal for viewing transaction image -->
<div class="modal fade" id="transactionImageModal" tabindex="-1" role="dialog" aria-labelledby="transactionImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionImageModalLabel">หลักฐานการโอนเงิน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="transaction-image-preview" src="" alt="หลักฐานการโอนเงิน" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for uploading new transaction -->
<div class="modal fade" id="uploadTransactionModal" tabindex="-1" role="dialog" aria-labelledby="uploadTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadTransactionModalLabel">อัปโหลดหลักฐานการโอนเงิน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/booking/transaction/upload" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="image">รูปภาพหลักฐานการโอนเงิน</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                        <small class="form-text text-muted">รองรับไฟล์ภาพ jpg, png, gif ขนาดไม่เกิน 10MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">อัปโหลด</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'เลือกข้อมูล',
            width: '100%'
        });

        // Calculate prices
        function calculatePrices() {
            let servicePrice = parseFloat($('#service_id option:selected').data('price')) || 0;
            let discount = parseFloat($('#promotion_id option:selected').data('discount')) || 0;

            // Set price
            $('#price').val(servicePrice);
            $('#price_display').val(servicePrice.toFixed(2) + ' บาท');

            // Set discount
            $('#discount').val(discount);
            $('#discount_display').val(discount.toFixed(2) + ' บาท');

            // Calculate total price
            let totalPrice = servicePrice - discount;
            if (totalPrice < 0) totalPrice = 0;
            $('#total_price').val(totalPrice);
            $('#total_price_display').val(totalPrice.toFixed(2) + ' บาท');

            // Calculate deposit (10% of total price)
            let depositPrice = totalPrice * 0.1;
            $('#deposit_price').val(depositPrice);
            $('#deposit_price_display').val(depositPrice.toFixed(2) + ' บาท');

            // Update service image
            let serviceImage = $('#service_id option:selected').data('image');
            if (serviceImage) {
                $('#service_image_preview').attr('src', serviceImage);
            } else {
                $('#service_image_preview').attr('src', '/assets/images/no-image.jpg');
            }
        }

        // Events for recalculation
        $('#service_id').change(function() {
            calculatePrices();
        });

        $('#promotion_id').change(function() {
            calculatePrices();
        });

        // Validate appointment datetime (can't be in the past)
        $('#appointment_date, #appointment_time').change(function() {
            let selectedDate = $('#appointment_date').val();
            let selectedTime = $('#appointment_time').val();

            if (selectedDate && selectedTime) {
                let appointmentDateTime = new Date(selectedDate + 'T' + selectedTime);
                let now = new Date();

                // Allow editing past appointments for historical records
                // But show a warning
                if (appointmentDateTime < now) {
                    Swal.fire({
                        title: 'คำเตือน',
                        text: 'คุณกำลังแก้ไขการจองในอดีต',
                        icon: 'warning',
                        confirmButtonText: 'รับทราบ'
                    });
                }
            }
        });

        // Form validation
        $('form').submit(function(e) {
            // Check if required fields are filled
            let isValid = true;

            if ($('#customer_id').val() === null) {
                alert('กรุณาเลือกลูกค้า');
                isValid = false;
            }

            if ($('#service_id').val() === null) {
                alert('กรุณาเลือกบริการ');
                isValid = false;
            }

            if ($('#appointment_date').val() === '') {
                alert('กรุณาเลือกวันที่นัดหมาย');
                isValid = false;
            }

            if ($('#appointment_time').val() === '') {
                alert('กรุณาเลือกเวลานัดหมาย');
                isValid = false;
            }

            return isValid;
        });

        $('.view-transaction-image').click(function() {
            var imageUrl = $(this).data('image');
            $('#transaction-image-preview').attr('src', imageUrl);

            // Get transaction ID from data attribute if available
            var transactionId = $(this).data('id');
            if (transactionId) {
                $('#delete-transaction').attr('href', '/booking/transaction/delete/' + transactionId);
            } else {
                $('#delete-transaction').hide();
            }

            // Add confirmation to delete button
            $('#delete-transaction').off('click').on('click', function(e) {
                if (!confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>