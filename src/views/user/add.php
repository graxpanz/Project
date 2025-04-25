<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-user-cog"></i>
            เพิ่มข้อมูลผู้ใช้
        </h4>
        <a href="/user" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/user/insert" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group text-center">
                        <label for="customFile">รูปโปรไฟล์</label>
                        <div class="position-relative mb-3 mx-auto" style="width: 150px;">
                            <img id="preview" src="/assets/images/avatar.png" alt="Profile Preview"
                                class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="custom-file" style="width: 250px;">
                            <input type="file" class="custom-file-input" name="image" id="customFile" accept="image/*">
                            <label class="custom-file-label text-truncate text-left" for="customFile" data-browse="เลือกรูป"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 px-1 px-md-5 mt-3">
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
                        <label for="address">ที่อยู่</label>
                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="ที่อยู่" required></textarea>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5 mt-3">
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="ชื่อผู้ใช้" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน" required>
                    </div>
                    <div class="form-group">
                        <label for="user_role_id">สิทธิ์การใช้งาน</label>
                        <select class="form-control" name="user_role_id" id="user_role_id" required>
                            <option value="" disabled selected>เลือกสิทธิ์การใช้งาน</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['user_role_id'] ?>"><?= $role['name'] ?></option>
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
        $('.custom-file-input').on('change', function(e) {
            const file = e.target.files[0];
            const $label = $(this).next('.custom-file-label');
            $label.html(file.name);
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                $(this).val('');
                $label.html('เลือกรูปภาพ');
                $('#preview').attr('src', '/assets/images/avatar.png');
            }
        });
    });
</script>