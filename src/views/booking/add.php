<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-calendar-plus"></i>
            เพิ่มข้อมูลการจอง
        </h4>
        <a href="/booking" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/booking/insert" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="customer_id">ลูกค้า</label>
                        <select class="form-control select2" name="customer_id" id="customer_id" required>
                            <option value="" disabled selected>เลือกลูกค้า</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>"><?= $customer['firstname'] . ' ' . $customer['lastname'] . ' (' . $customer['phone'] . ')' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="service_id">บริการ</label>
                        <select class="form-control select2" name="service_id" id="service_id" required>
                            <option value="" disabled selected>เลือกบริการ</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['service_id'] ?>" 
                                        data-price="<?= $service['price'] ?>" 
                                        data-time="<?= $service['time'] ?>"
                                        data-image="<?= !empty($service['image']) ? '/assets/uploads/service/' . $service['image'] : '/assets/images/no-image.jpg' ?>">
                                    <?= $service['name'] ?> - <?= $service['price'] ?> บาท (<?= $service['time'] ?> นาที)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_date">วันที่นัดหมาย</label>
                        <input type="date" class="form-control" name="appointment_date" id="appointment_date" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_time">เวลานัดหมาย</label>
                        <input type="time" class="form-control" name="appointment_time" id="appointment_time" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="user_id">ผู้ให้บริการ</label>
                        <select class="form-control select2" name="user_id" id="user_id">
                            <option value="" selected>ไม่ระบุ (กำหนดภายหลัง)</option>
                            <?php foreach ($employees as $employee): ?>
                                <option value="<?= $employee['user_id'] ?>">
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
                            <option value="" selected>ไม่มีโปรโมชั่น</option>
                            <?php foreach ($promotions as $promotion): ?>
                                <option value="<?= $promotion['promotion_id'] ?>" data-discount="<?= $promotion['discount'] ?>">
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
                            <option value="pending" selected>รอยืนยัน</option>
                            <option value="confirm">ยืนยันแล้ว</option>
                            <option value="cancel">ยกเลิก</option>
                            <option value="complete">เสร็จสิ้น</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="note">หมายเหตุ</label>
                        <textarea class="form-control" name="note" id="note" rows="3" placeholder="หมายเหตุเพิ่มเติม"></textarea>
                    </div>
                    
                    <div class="card border-info mt-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">สรุปการจอง</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <img id="service_image_preview" src="/assets/images/no-image.jpg" alt="Service Preview"
                                        class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">ราคาบริการ:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext" id="price_display" value="0.00 บาท" readonly>
                                            <input type="hidden" name="price" id="price" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">ส่วนลดโปรโมชั่น:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext text-danger" id="discount_display" value="0.00 บาท" readonly>
                                            <input type="hidden" name="discount" id="discount" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label">มัดจำ (10%):</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext text-primary" id="deposit_price_display" value="0.00 บาท" readonly>
                                            <input type="hidden" name="deposit_price" id="deposit_price" value="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-6 col-form-label font-weight-bold">ราคาสุทธิ:</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control-plaintext font-weight-bold text-success" id="total_price_display" value="0.00 บาท" readonly>
                                            <input type="hidden" name="total_price" id="total_price" value="0">
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
            console.log(serviceImage)
            if (serviceImage) {
                console.log(1)
                $('#service_image_preview').attr('src', serviceImage);
            } else {
                console.log(2)
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
                
                if (appointmentDateTime < now) {
                    alert('ไม่สามารถจองเวลาในอดีตได้ กรุณาเลือกเวลาใหม่');
                    $('#appointment_time').val('');
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
    });
</script>