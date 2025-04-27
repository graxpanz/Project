<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-comment"></i>
            แก้ไขข้อมูลความคิดเห็น
        </h4>
        <a href="/feedback" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/feedback/update" method="POST">
        <input type="hidden" name="feedback_id" value="<?= $feedback['feedback_id'] ?>">
        <div class="card-body">
            <div class="row">
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="customer_id">ลูกค้า</label>
                        <select class="form-control" name="customer_id" id="customer_id" required>
                            <option value="" disabled>เลือกลูกค้า</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= ($customer['customer_id'] == $feedback['customer_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service_id">บริการ</label>
                        <select class="form-control" name="service_id" id="service_id">
                            <option value="">ไม่ระบุบริการ</option>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['service_id'] ?>" <?= ($service['service_id'] == $feedback['service_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($service['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="rating">คะแนน</label>
                        <div class="rating-container">
                            <div class="star-rating-select">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="rating-<?= $i ?>" name="rating" value="<?= $i ?>" <?= ($i == $feedback['rating']) ? 'checked' : '' ?>>
                                    <label for="rating-<?= $i ?>"><i class="fas fa-star"></i></label>
                                <?php endfor; ?>
                            </div>
                            <div class="rating-text mt-2">
                                <span id="rating-value"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการแสดงผล</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" <?= ($feedback['is_active'] == '1') ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= ($feedback['is_active'] == '0') ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="comment">ความคิดเห็น</label>
                        <textarea class="form-control" name="comment" id="comment" rows="4" placeholder="ความคิดเห็นจากลูกค้า"><?= htmlspecialchars($feedback['comment'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label>วันที่สร้าง:</label>
                        <p class="form-control-static"><?= $this->dateFormat($feedback['created_at']) ?></p>
                    </div>
                    <div class="form-group">
                        <label>แก้ไขล่าสุด:</label>
                        <p class="form-control-static"><?= $this->dateFormat($feedback['updated_at']) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>

<style>
    .rating-container {
        width: 100%;
    }
    
    .star-rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .star-rating-select input {
        display: none;
    }
    
    .star-rating-select label {
        font-size: 1.8rem;
        color: #ddd;
        cursor: pointer;
        margin-right: 5px;
        transition: all 0.2s ease;
    }
    
    .star-rating-select label:hover,
    .star-rating-select label:hover ~ label,
    .star-rating-select input:checked ~ label {
        color: #FFD700;
    }
</style>

<script>
    $(document).ready(function() {
        // Select2 for dropdowns
        $('#customer_id, #service_id').select2({
            placeholder: 'เลือกข้อมูล',
            width: '100%'
        });
        
        // Initialize rating text
        function updateRatingText(value) {
            let ratingText = '';
            switch(parseInt(value)) {
                case 1:
                    ratingText = 'แย่มาก (1/5)';
                    break;
                case 2:
                    ratingText = 'แย่ (2/5)';
                    break;
                case 3:
                    ratingText = 'ปานกลาง (3/5)';
                    break;
                case 4:
                    ratingText = 'ดี (4/5)';
                    break;
                case 5:
                    ratingText = 'ดีมาก (5/5)';
                    break;
                default:
                    ratingText = 'ไม่ได้ให้คะแนน';
            }
            $('#rating-value').text(ratingText);
        }
        
        // Set initial rating text
        updateRatingText($('input[name="rating"]:checked').val());
        
        // Update rating text when selection changes
        $('input[name="rating"]').change(function() {
            updateRatingText($(this).val());
        });
        
        // Form validation
        $('form').on('submit', function(e) {
            if ($('#customer_id').val() === null) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณาเลือกลูกค้า',
                    text: 'กรุณาเลือกลูกค้าก่อนบันทึกข้อมูล'
                });
                return false;
            }
            
            if (!$('input[name="rating"]:checked').val()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'กรุณาให้คะแนน',
                    text: 'กรุณาให้คะแนนก่อนบันทึกข้อมูล'
                });
                return false;
            }
            
            return true;
        });
    });
</script>