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
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="first_name">ชื่อจริง</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="ชื่อจริง" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">นามสกุล</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="นามสกุล" required>
                    </div>
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้งาน</label>
                        <input type="text" class="form-control" name="username" id="username" placeholder="ชื่อผู้ใช้งาน" required>
                    </div>
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="รหัสผ่าน" required>
                    </div>
                </div>
                <div class="col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="status">สิทธิ์การใช้งาน</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="" disabled selected>กำหนดสิทธิ์</option>
                            <option value="superadmin">Super Admin</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="customFile">รูปโปรไฟล์</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file" id="customFile" accept="image/*">
                            <label class="custom-file-label" for="customFile">เลือกรูปภาพ</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>