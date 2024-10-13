<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            เพิ่มข้อมูลพนักงาน
        </h4>
        <a href="/employee" class="btn btn-info mt-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <form action="/employee/insert" method="POST">
            <div class="mb-3">
                <label for="fname" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="mb-3">
                <label for="lname" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="mb-3">
                <label for="bdate" class="form-label">วัน/เดือน/ปีเกิด</label>
                <input type="date" class="form-control" id="bdate" name="bdate" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">อายุ</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>
            <div class="mb-3">
                <label for="tel" class="form-label">เบอร์ติดต่อ</label>
                <input type="text" class="form-control" id="tel" name="tel" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label>เลือกตำแหน่งงานบริการ</label><br>
                <?php foreach ($positions as $position): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="position<?php echo $position['position_id']; ?>" name="position[]" value="<?php echo $position['position_id']; ?>">
                        <label class="form-check-label" for="position<?php echo $position['position_id']; ?>">
                            <?php echo htmlspecialchars($position['position_name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>