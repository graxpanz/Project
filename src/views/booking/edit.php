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
            <div class="row">
                <div class="col-md-6 mb-2 mb-md-0">
                    <button type="submit" class="btn btn-primary btn-block" name="submit">บันทึกข้อมูล</button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#receiptModal">
                        <i class="fas fa-print"></i> พิมพ์ใบเสร็จชำระเงิน
                    </button>
                </div>
            </div>
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

<!-- Modal สำหรับพิมพ์ใบเสร็จชำระเงิน -->
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">พิมพ์ใบเสร็จชำระเงิน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_amount">จำนวนเงินที่ชำระ (บาท)</label>
                            <input type="number" class="form-control" id="payment_amount" step="0.01" min="0" value="<?= $booking['total_price'] ?? 0 ?>">
                        </div>
                        <div class="form-group">
                            <label for="payment_method">วิธีการชำระเงิน</label>
                            <select class="form-control" id="payment_method">
                                <option value="เงินสด">เงินสด</option>
                                <option value="โอนเงิน">โอนเงิน</option>
                                <option value="บัตรเครดิต/เดบิต">บัตรเครดิต/เดบิต</option>
                                <option value="QR Payment">QR Payment</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_note">หมายเหตุการชำระเงิน</label>
                            <textarea class="form-control" id="payment_note" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">สรุปรายการ</h6>
                            </div>
                            <div class="card-body p-2">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <td>ลูกค้า:</td>
                                        <td><span id="receipt_customer_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td>บริการ:</td>
                                        <td><span id="receipt_service_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td>วันที่ให้บริการ:</td>
                                        <td><span id="receipt_service_date"></span></td>
                                    </tr>
                                    <tr>
                                        <td>ราคาบริการ:</td>
                                        <td><span id="receipt_service_price"></span> บาท</td>
                                    </tr>
                                    <tr>
                                        <td>ส่วนลด:</td>
                                        <td><span id="receipt_discount"></span> บาท</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><strong>ยอดรวมชำระ:</strong></td>
                                        <td><strong><span id="receipt_total"></span> บาท</strong></td>
                                    </tr>
                                    <tr>
                                        <td>รับเงิน:</td>
                                        <td><span id="receipt_payment_amount"></span> บาท</td>
                                    </tr>
                                    <tr>
                                        <td>เงินทอน:</td>
                                        <td><span id="receipt_change"></span> บาท</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-primary" id="printReceiptBtn">
                    <i class="fas fa-print"></i> พิมพ์ใบเสร็จ
                </button>
            </div>
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

    $('#receiptModal').on('show.bs.modal', function() {
        // อัพเดตข้อมูลในหน้าต่างใบเสร็จ
        const customerName = $('#customer_id option:selected').text();
        const serviceName = $('#service_id option:selected').text().split(' - ')[0];
        const servicePrice = parseFloat($('#price').val()) || 0;
        const discount = parseFloat($('#discount').val()) || 0;
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const appointmentDate = $('#appointment_date').val();
        const appointmentTime = $('#appointment_time').val();
        
        // แสดงข้อมูลในตาราง preview
        $('#receipt_customer_name').text(customerName);
        $('#receipt_service_name').text(serviceName);
        $('#receipt_service_date').text(formatThaiDate(appointmentDate) + ' ' + appointmentTime + ' น.');
        $('#receipt_service_price').text(servicePrice.toFixed(2));
        $('#receipt_discount').text(discount.toFixed(2));
        $('#receipt_total').text(totalPrice.toFixed(2));
        
        // ตั้งค่าเริ่มต้นสำหรับจำนวนเงินที่ชำระ
        $('#payment_amount').val(totalPrice.toFixed(2));
        updateChange();
    });

    // คำนวณเงินทอน
    $('#payment_amount').on('input', function() {
        updateChange();
    });

    function updateChange() {
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        const change = paymentAmount - totalPrice;
        
        $('#receipt_payment_amount').text(paymentAmount.toFixed(2));
        $('#receipt_change').text(change >= 0 ? change.toFixed(2) : '0.00');
    }

    // พิมพ์ใบเสร็จ
    $('#printReceiptBtn').on('click', function() {
        const customerName = $('#customer_id option:selected').text();
        const serviceName = $('#service_id option:selected').text().split(' - ')[0];
        const servicePrice = parseFloat($('#price').val()) || 0;
        const discount = parseFloat($('#discount').val()) || 0;
        const totalPrice = parseFloat($('#total_price').val()) || 0;
        const appointmentDate = $('#appointment_date').val();
        const appointmentTime = $('#appointment_time').val();
        const paymentAmount = parseFloat($('#payment_amount').val()) || 0;
        const change = paymentAmount - totalPrice;
        const paymentMethod = $('#payment_method').val();
        const paymentNote = $('#payment_note').val();
        
        // สร้างหน้าต่างใหม่สำหรับการพิมพ์
        const printWindow = window.open('', '_blank');
        
        // เตรียม HTML
        let htmlContent = '<!DOCTYPE html><html><head>';
        htmlContent += '<title>ใบเสร็จรับเงิน - Mira</title>';
        htmlContent += '<meta charset="utf-8">';
        htmlContent += '<style>';
        htmlContent += '@import url("https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap");';
        htmlContent += 'body { font-family: "Sarabun", sans-serif; padding: 20px; color: #000; }';
        htmlContent += '.receipt-header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; }';
        htmlContent += '.receipt-footer { margin-top: 30px; text-align: center; font-size: 12px; }';
        htmlContent += 'table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }';
        htmlContent += 'table, th, td { border: 1px solid #ddd; }';
        htmlContent += 'th, td { padding: 10px; text-align: left; }';
        htmlContent += '.text-right { text-align: right; }';
        htmlContent += '.receipt-total { font-weight: bold; background-color: #f8f9fa; }';
        htmlContent += '.signature-section { margin-top: 50px; display: flex; justify-content: space-between; }';
        htmlContent += '.signature-box { width: 45%; text-align: center; }';
        htmlContent += '.signature-line { border-top: 1px solid #000; margin-top: 50px; margin-bottom: 10px; }';
        htmlContent += '@media print { @page { maring: 0 } body, * { color: #000 !important; } }';
        htmlContent += '</style>';
        htmlContent += '</head><body>';
        
        // ส่วนหัวใบเสร็จ
        htmlContent += '<div class="receipt-header">';
        htmlContent += '<h2>ใบเสร็จรับเงิน</h2>';
        htmlContent += '<h4>Mira ศูนย์ความงามครบวงจร</h4>';
        htmlContent += '<p>เลขที่ใบเสร็จ: MR' + new Date().getFullYear() + padZero(new Date().getMonth() + 1) + padZero(new Date().getDate()) + '-' + Math.floor(Math.random() * 10000) + '</p>';
        htmlContent += '<p>วันที่ออกใบเสร็จ: ' + new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' }) + '</p>';
        htmlContent += '</div>';
        
        // ข้อมูลลูกค้า
        htmlContent += '<table>';
        htmlContent += '<tr><td colspan="2"><strong>ข้อมูลลูกค้า</strong></td></tr>';
        htmlContent += '<tr><td>ชื่อลูกค้า:</td><td>' + customerName + '</td></tr>';
        htmlContent += '<tr><td>วันที่รับบริการ:</td><td>' + formatThaiDate(appointmentDate) + ' ' + appointmentTime + ' น.</td></tr>';
        htmlContent += '</table>';
        
        // รายการบริการ
        htmlContent += '<table>';
        htmlContent += '<tr>';
        htmlContent += '<th style="width: 5%;">#</th>';
        htmlContent += '<th style="width: 55%;">รายการ</th>';
        htmlContent += '<th style="width: 20%;" class="text-right">ราคา (บาท)</th>';
        htmlContent += '<th style="width: 20%;" class="text-right">รวม (บาท)</th>';
        htmlContent += '</tr>';
        
        // บริการหลัก
        htmlContent += '<tr>';
        htmlContent += '<td>1</td>';
        htmlContent += '<td>' + serviceName + '</td>';
        htmlContent += '<td class="text-right">' + servicePrice.toFixed(2) + '</td>';
        htmlContent += '<td class="text-right">' + servicePrice.toFixed(2) + '</td>';
        htmlContent += '</tr>';
        
        // ส่วนลด (ถ้ามี)
        if (discount > 0) {
            htmlContent += '<tr>';
            htmlContent += '<td colspan="3" class="text-right">ส่วนลด:</td>';
            htmlContent += '<td class="text-right">-' + discount.toFixed(2) + '</td>';
            htmlContent += '</tr>';
        }
        
        // ยอดรวม
        htmlContent += '<tr class="receipt-total">';
        htmlContent += '<td colspan="3" class="text-right"><strong>ยอดรวมทั้งสิ้น:</strong></td>';
        htmlContent += '<td class="text-right"><strong>' + totalPrice.toFixed(2) + '</strong></td>';
        htmlContent += '</tr>';
        
        htmlContent += '<tr>';
        htmlContent += '<td colspan="3" class="text-right">รับเงิน (' + paymentMethod + '):</td>';
        htmlContent += '<td class="text-right">' + paymentAmount.toFixed(2) + '</td>';
        htmlContent += '</tr>';
        
        htmlContent += '<tr>';
        htmlContent += '<td colspan="3" class="text-right">เงินทอน:</td>';
        htmlContent += '<td class="text-right">' + (change > 0 ? change.toFixed(2) : '0.00') + '</td>';
        htmlContent += '</tr>';
        htmlContent += '</table>';
        
        // หมายเหตุ (ถ้ามี)
        if (paymentNote) {
            htmlContent += '<p><strong>หมายเหตุ:</strong> ' + paymentNote + '</p>';
        }
        
        // ส่วนลงลายมือชื่อ
        htmlContent += '<div class="signature-section">';
        htmlContent += '<div class="signature-box">';
        htmlContent += '</div>';
        
        htmlContent += '<div class="signature-box">';
        htmlContent += '<div class="signature-line"></div>';
        htmlContent += '<p>ผู้รับเงิน</p>';
        htmlContent += '</div>';
        htmlContent += '</div>';
        
        // ส่วนท้าย
        htmlContent += '<div class="receipt-footer">';
        htmlContent += '<p>ขอบคุณที่ใช้บริการ Mira ศูนย์ความงามครบวงจร</p>';
        htmlContent += '<p>เอกสารนี้เป็นใบเสร็จรับเงิน/ใบกำกับภาษีอย่างย่อ</p>';
        htmlContent += '</div>';
        
        // สคริปต์สำหรับการพิมพ์
        htmlContent += '<script>';
        htmlContent += 'window.onload = function() {';
        htmlContent += '  setTimeout(function() {';
        htmlContent += '    window.print();';
        htmlContent += '    setTimeout(function() { window.close(); }, 500);';
        htmlContent += '  }, 500);';
        htmlContent += '};';
        htmlContent += '<\/script>';
        
        htmlContent += '</body></html>';
        
        // เขียน HTML ลงในหน้าต่างที่เปิด
        printWindow.document.open();
        printWindow.document.write(htmlContent);
        printWindow.document.close();
    });

    // ฟังก์ชันช่วยจัดรูปแบบวันที่เป็นไทย
    function formatThaiDate(dateString) {
        const date = new Date(dateString);
        const thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];
        return `${date.getDate()} ${thaiMonths[date.getMonth()]} ${date.getFullYear() + 543}`;
    }

    // ฟังก์ชันเติม 0 ข้างหน้าตัวเลข
    function padZero(num) {
        return String(num).padStart(2, '0');
    }
</script>