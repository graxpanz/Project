<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-comment"></i>
            ตอบกลับการประเมิน
        </h4>
        <a href="/estimate" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-12 px-1 px-md-5">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ข้อมูลลูกค้า: <?= htmlspecialchars($estimate['customer_name']) ?>
                                </div>
                                <div class="text-sm mb-0 text-muted">
                                    โทรศัพท์: <?= htmlspecialchars($estimate['customer_phone']) ?>
                                </div>
                                <?php if (!empty($estimate['image'])): ?>
                                <div class="mt-3 text-center">
                                    <img src="/assets/uploads/estimate/<?= $estimate['image'] ?>" 
                                         alt="Estimate Image" 
                                         class="img-fluid" 
                                         style="max-height: 300px; width: auto;">
                                </div>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <h6 class="font-weight-bold">รายละเอียดการประเมิน:</h6>
                                    <p><?= nl2br(htmlspecialchars($estimate['description'])) ?></p>
                                </div>
                                <div class="mt-2 text-muted">
                                    <small>วันที่สร้าง: <?= $this->dateFormat($estimate['created_at']) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="/estimate/update_response" method="POST">
            <input type="hidden" name="estimate_id" value="<?= $estimate['estimate_id'] ?>">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group">
                        <label for="response">ตอบกลับการประเมิน</label>
                        <textarea class="form-control" name="response" id="response" rows="6" placeholder="กรอกข้อความตอบกลับไปยังลูกค้า"><?= $estimate['response'] ?? '' ?></textarea>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-info-circle"></i> 
                            การดำเนินการนี้จะเปลี่ยนสถานะการประเมินเป็น "ตอบกลับแล้ว" โดยอัตโนมัติ
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm-response" required>
                            <label class="form-check-label" for="confirm-response">
                                ยืนยันการตอบกลับการประเมิน
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block mx-auto w-50" id="submit-btn" disabled>
                    <i class="fas fa-paper-plane"></i> ส่งการตอบกลับ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Enable submit button only when checkbox is checked
        $('#confirm-response').change(function() {
            if(this.checked) {
                $('#submit-btn').prop('disabled', false);
            } else {
                $('#submit-btn').prop('disabled', true);
            }
        });
    });
</script>