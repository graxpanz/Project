<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-edit"></i>
            แก้ไขวัสดุสิ้นเปลือง
        </h4>
        <a href="/supply" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/supply/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="supply_id" value="<?= $supply['supply_id'] ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปภาพ</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 200px;">
                            <img id="preview"
                                src="<?= !empty($supply['image']) ? '/assets/uploads/supplies/' . $supply['image'] : '/assets/images/no-image.jpg' ?>"
                                alt="Supply Preview"
                                class="img-thumbnail" style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="custom-file" style="width: 300px;">
                            <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*">
                            <label class="custom-file-label text-truncate text-left" for="customFile" data-browse="เลือกรูป"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="name">ชื่อวัสดุสิ้นเปลือง <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="ชื่อวัสดุสิ้นเปลือง" value="<?= htmlspecialchars($supply['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">หน่วย <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="unit" id="unit" placeholder="เช่น ชิ้น, กล่อง, ขวด" value="<?= htmlspecialchars($supply['unit']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="min_quantity">จำนวนขั้นต่ำ</label>
                        <input type="number" class="form-control" name="min_quantity" id="min_quantity" placeholder="จำนวนขั้นต่ำที่ควรมี" min="0" value="<?= $supply['min_quantity'] ?>">
                        <small class="form-text text-muted">ระบบจะแจ้งเตือนเมื่อสินค้าเหลือน้อยกว่าหรือเท่ากับจำนวนนี้</small>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea class="form-control" name="description" id="description" rows="6" placeholder="รายละเอียดเพิ่มเติม"><?= htmlspecialchars($supply['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" <?= $supply['is_active'] == '1' ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= $supply['is_active'] == '0' ? 'selected' : '' ?>>ปิดใช้งาน</option>
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
    });
</script>