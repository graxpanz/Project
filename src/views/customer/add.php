<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            เพิ่มข้อมูลลูกค้า
        </h4>
        <a href="/customer" class="btn btn-info mt-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <form action="/customer/insert" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์ติดต่อ</label>
                <input type="number" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">อายุ</label>
                <input type="date" class="form-control" id="age" name="age" required>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
            </div>
    </div>
    </form>
</div>
