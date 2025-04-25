<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i>
            แก้ไขข้อมูลโปรโมชั่น
        </h4>
        <a href="/promotion" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/promotion/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="promotion_id" value="<?= $promotion['promotion_id'] ?>">
        <input type="hidden" name="old_image" value="<?= $promotion['image'] ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปภาพ</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 360px;">
                            <img id="preview"
                                src="<?= !empty($promotion['image']) ? '/assets/uploads/promotion/' . $promotion['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Promotion Preview"
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
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="name">ชื่อโปรโมชั่น</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="ชื่อโปรโมชั่น" value="<?= $promotion['name'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="code">โค้ดส่วนลด</label>
                        <input type="text" class="form-control" name="code" id="code" placeholder="โค้ดส่วนลดอย่างน้อย 6 ตัว" minlength="6" value="<?= $promotion['code'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea class="form-control" name="description" id="description" rows="6" placeholder="รายละเอียด"><?= $promotion['description'] ?></textarea>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="discount">ส่วนลด (บาท)</label>
                        <input type="number" class="form-control" name="discount" id="discount" placeholder="ส่วนลด" min="0.00" step="1.00" value="<?= $promotion['discount'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="start_datetime">วันที่เริ่มโปรโมชั่น</label>
                        <input type="datetime-local" class="form-control" name="start_datetime" id="start_datetime" placeholder="วันที่เริ่มโปรโมชั่น" value="<?= $promotion['start_datetime'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="end_datetime">วันที่สิ้นสุดโปรโมชั่น</label>
                        <input type="datetime-local" class="form-control" name="end_datetime" id="end_datetime" placeholder="วันที่สิ้นสุดโปรโมชั่น" value="<?= $promotion['end_datetime'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" <?= $promotion['is_active'] == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= $promotion['is_active'] == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
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