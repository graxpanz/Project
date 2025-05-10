<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-concierge-bell"></i>
            เพิ่มข้อมูลการบริการ
        </h4>
        <a href="/service" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/service/insert" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปภาพ</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 360px;">
                            <img id="preview" src="/assets/images/no-image.jpg" alt="Service Preview"
                                class="img-thumbnail" style="width: 360px; height: auto; object-fit: cover;">
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
                        <label for="name">ชื่อบริการ</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="ชื่อบริการ" required>
                    </div>
                    <div class="form-group">
                        <label for="service_type_id">ประเภทบริการ</label>
                        <select class="form-control" name="service_type_id" id="service_type_id" required>
                            <option value="" disabled selected>เลือกประเภทบริการ</option>
                            <?php foreach ($service_types as $service_type): ?>
                                <option value="<?= $service_type['service_type_id'] ?>"><?= $service_type['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea class="form-control" name="description" id="description" rows="6" placeholder="รายละเอียด"></textarea>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="price">ราคา (บาท)</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="ราคา" min="0.00" step="1.00" required>
                    </div>
                    <div class="form-group">
                        <label for="time">เวลาในการบริการ (นาที)</label>
                        <input type="number" class="form-control" name="time" id="time" placeholder="เวลา" min="0" step="1" required>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" selected>เปิดใช้งาน</option>
                            <option value="0">ปิดใช้งาน</option>
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