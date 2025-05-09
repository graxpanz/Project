<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-clipboard-list"></i>
            แก้ไขข้อมูลการประเมิน
        </h4>
        <a href="/estimate" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/estimate/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="estimate_id" value="<?= $estimate['estimate_id'] ?>">
        <input type="hidden" name="old_image" value="<?= $estimate['image'] ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปภาพ</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 360px;">
                            <img id="preview"
                                src="<?= !empty($estimate['image']) ? '/assets/uploads/estimate/' . $estimate['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Estimate Preview"
                                class="img-thumbnail"
                                style="width: 360px; height: auto; object-fit: cover;">
                        </div>
                        <div class="custom-file" style="width: 360px;">
                            <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*">
                            <label class="custom-file-label text-truncate text-left" for="customFile" data-browse="เลือกรูป"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-12 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="customer_id">ลูกค้า</label>
                        <select class="form-control" name="customer_id" id="customer_id" required>
                            <option value="" disabled>เลือกลูกค้า</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['customer_id'] ?>" <?= $customer['customer_id'] == $estimate['customer_id'] ? 'selected' : '' ?>>
                                    <?= $customer['firstname'] ?> <?= $customer['lastname'] ?> (<?= $customer['phone'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">รายละเอียดการประเมิน</label>
                        <textarea class="form-control" name="description" id="description" rows="6" placeholder="รายละเอียดการประเมิน"><?= $estimate['description'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="response">การตอบกลับ</label>
                        <textarea class="form-control" name="response" id="response" rows="6" placeholder="การตอบกลับ"><?= $estimate['response'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">สถานะ</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="pending" <?= $estimate['status'] == 'pending' ? 'selected' : '' ?>>รอการตอบกลับ</option>
                            <option value="responsed" <?= $estimate['status'] == 'responsed' ? 'selected' : '' ?>>ตอบกลับแล้ว</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" <?= $estimate['is_active'] == '1' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= $estimate['is_active'] == '0' ? 'selected' : '' ?>>ปิดใช้งาน</option>
                        </select>
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
        $('.custom-file-input').on('change', function(e) {
            const file = e.target.files[0];
            const $label = $(this).next('.custom-file-label');

            if (file) {
                // ตรวจสอบขนาดไฟล์ (ไม่เกิน 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('ขนาดไฟล์ต้องไม่เกิน 10MB');
                    $(this).val('');
                    $label.html('เลือกรูป');
                    return;
                }

                if (file.type.startsWith('image/')) {
                    $label.html(file.name);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                } else {
                    alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น');
                    $(this).val('');
                    $label.html('เลือกรูป');
                }
            }
        });

        // Hover effect
        $('.position-relative').hover(
            function() {
                $('#preview-overlay').css('opacity', '1');
            },
            function() {
                $('#preview-overlay').css('opacity', '0');
            }
        );
    });
</script>