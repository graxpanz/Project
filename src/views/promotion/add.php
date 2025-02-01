<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-tag"></i>
            เพิ่มข้อมูลโปรโมชั่น
        </h4>
        <a href="/promotion" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/promotion/insert" method="POST">
        <div class="card-body">
            <div class="mb-3">
                <label for="promotion_name" class="form-label">ชื่อโปรโมชั่น</label>
                <input type="text" class="form-control" id="promotion_name" name="promotion_name" required>
            </div>
            <div class="mb-3">
                <label for="detail" class="form-label">รายละเอียด</label>
                <textarea class="form-control" id="detail" name="detail" required></textarea>
            </div>
            <div class="mb-3">
                <label for="date_start" class="form-label">วันที่เริ่มโปรโมชั่น</label>
                <input type="date" class="form-control" id="date_start" name="date_start" required>
            </div>
            <div class="mb-3">
                <label for="date_end" class="form-label">วันที่สิ้นสุดโปรโมชั่น</label>
                <input type="date" class="form-control" id="date_end" name="date_end" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>