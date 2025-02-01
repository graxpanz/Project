<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i>
            แก้ไขข้อมูลผู้ดูแล
        </h4>
        <a href="/manager" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/manager/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= $manager['user_id'] ?>">
        <input type="hidden" name="old_image" value="<?= $manager['image'] ?>">

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label>รูปโปรไฟล์</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 150px;">
                            <img id="preview"
                                src="<?= !empty($manager['image']) ? '/uploads/' . $manager['image'] : '/images/default-profile.png' ?>"
                                alt="Profile Preview"
                                class="img-thumbnail rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center rounded-circle"
                                style="top: 0; left: 0; background: rgba(0,0,0,0.5); opacity: 0; transition: all 0.3s;"
                                id="preview-overlay">
                                <i class="fas fa-camera text-white" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <div class="custom-file" style="width: 250px;">
                            <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*">
                            <label class="custom-file-label text-truncate" for="customFile" data-browse="เลือกรูป">
                                เลือกรูปภาพ
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname">ชื่อจริง</label>
                        <input type="text" class="form-control" name="firstname" id="firstname"
                            value="<?= $manager['firstname'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">นามสกุล</label>
                        <input type="text" class="form-control" name="lastname" id="lastname"
                            value="<?= $manager['lastname'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="<?= $manager['email'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" name="phone" id="phone"
                            value="<?= $manager['phone'] ?>" pattern="[0-9]{10}" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">วันเกิด</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate"
                            value="<?= $manager['birthdate'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="age">อายุ</label>
                        <input type="number" class="form-control" name="age" id="age"
                            value="<?= $manager['age'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="address">ที่อยู่</label>
                        <textarea class="form-control" name="address" id="address" rows="3" required><?= $manager['address'] ?></textarea>
                    </div>
                </div>
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" name="username" id="username"
                            value="<?= $manager['username'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่านใหม่ (เว้นว่างถ้าไม่ต้องการเปลี่ยน)</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group">
                        <label for="role_id">สิทธิ์การใช้งาน</label>
                        <select class="form-control" name="role_id" id="role_id" required>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['role_id'] ?>" <?= $role['role_id'] == $manager['role_id'] ? 'selected' : '' ?>>
                                    <?= $role['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" name="is_active" id="is_active" required>
                            <option value="1" <?= $manager['is_active'] == 1 ? 'selected' : '' ?>>เปิดใช้งาน</option>
                            <option value="0" <?= $manager['is_active'] == 0 ? 'selected' : '' ?>>ปิดใช้งาน</option>
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
        // คำนวณอายุอัตโนมัติ
        $('#birthdate').on('change', function() {
            const birthDate = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            $('#age').val(age);
        });

        // แสดง preview เมื่อเลือกรูปภาพ
        $('.custom-file-input').on('change', function(e) {
            const file = e.target.files[0];
            const $label = $(this).next('.custom-file-label');

            if (file) {
                // ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('ขนาดไฟล์ต้องไม่เกิน 2MB');
                    resetFileInput($(this), $label);
                    return;
                }

                // ตรวจสอบประเภทไฟล์
                if (!file.type.startsWith('image/')) {
                    alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น');
                    resetFileInput($(this), $label);
                    return;
                }

                // แสดงชื่อไฟล์
                $label.html(file.name);

                // แสดง preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').fadeOut(200, function() {
                        $(this)
                            .attr('src', e.target.result)
                            .fadeIn(200);
                    });
                }
                reader.readAsDataURL(file);
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

        function resetFileInput($input, $label) {
            $input.val('');
            $label.html('เลือกรูปภาพ');
            // คืนค่ารูปเดิม
            $('#preview').attr('src', $('#preview').attr('src'));
        }
    });
</script>