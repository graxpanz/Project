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
        <input type="hidden" name="u_id" value="<?php echo htmlspecialchars($manager['u_id']); ?>">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="firstname">ชื่อจริง</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo htmlspecialchars($manager['firstname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">นามสกุล</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo htmlspecialchars($manager['lastname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($manager['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="ใส่รหัสผ่านเมื่อต้องการเปลี่ยน">
                    </div>
                </div>
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="status">สิทธิ์การใช้งาน</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="superadmin" <?php echo $manager['status'] == 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                            <option value="admin" <?php echo $manager['status'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customFile">รูปโปรไฟล์</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="customFile" accept="image/*">
                            <label class="custom-file-label" for="customFile">เลือกรูปภาพใหม่</label>
                        </div>
                        <?php if (!empty($manager['image'])): ?>
                            <img src="/uploads/managers/<?php echo htmlspecialchars($manager['image']); ?>" alt="Profile Image" class="img-fluid p-3">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกการแก้ไข</button>
        </div>
    </form>
</div>