<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i>
            เพิ่มข้อมูลผู้ดูแล
        </h4>
        <a href="/manager" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/manager/insert" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <!-- คอลัมน์ซ้าย -->
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปโปรไฟล์</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 150px;">
                            <img id="preview" src="/images/default-profile.png" alt="Profile Preview"
                                class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
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
                        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="ชื่อจริง" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">นามสกุล</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="นามสกุล" required>
                    </div>
                    <div class="form-group">
                        <label for="email">อีเมล</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="0xxxxxxxxx" pattern="[0-9]{10}" required>
                    </div>
                    <div class="form-group">
                        <label for="birthdate">วันเกิด</label>
                        <input type="date" class="form-control" name="birthdate" id="birthdate" required>
                    </div>
                    <div class="form-group">
                        <label for="age">อายุ</label>
                        <input type="number" class="form-control" name="age" id="age" min="1" max="120" readonly>
                    </div>
                    <div class="form-group">
                        <label for="address">ที่อยู่</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="ที่อยู่" required></textarea>
                    </div>
                </div>
                <!-- คอลัมน์ขวา -->
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="ชื่อผู้ใช้งาน" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน" required>
                    </div>
                    <div class="form-group">
                        <label for="role_id">สิทธิ์การใช้งาน</label>
                        <select class="form-control" name="role_id" id="role_id" required>
                            <option value="" disabled selected>เลือกสิทธิ์การใช้งาน</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['role_id'] ?>"><?= $role['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
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
        // คำนวณอายุอัตโนมัติเมื่อเลือกวันเกิด
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

            // แสดงชื่อไฟล์ที่เลือก
            const $label = $(this).next('.custom-file-label');
            $label.html(file.name);

            // ตรวจสอบว่าเป็นไฟล์รูปภาพหรือไม่
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(file);
            } else {
                // ถ้าไม่ใช่ไฟล์รูปภาพ
                alert('กรุณาเลือกไฟล์รูปภาพเท่านั้น');
                // รีเซ็ต input file
                $(this).val('');
                $label.html('เลือกรูปภาพ');
                // แสดงรูปภาพเริ่มต้น
                $('#preview').attr('src', '/images/default-profile.png');
            }
        });
    });
</script>