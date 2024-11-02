<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-wallet"></i>
            แก้ไขข้อมูลการเงิน
        </h4>
        <a href="/finance" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/finance/update" method="POST">
        <input type="hidden" name="finance_id" value="<?php echo htmlspecialchars($finance['finance_id']); ?>">
        <div class="card-body">
            <div class="mb-3">
                <label for="date" class="form-label">วันที่</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="<?php echo htmlspecialchars($finance['date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="list" class="form-label">รายการ</label>
                <input type="text" class="form-control" id="list" name="list"
                    value="<?php echo htmlspecialchars($finance['list']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="income" class="form-label">รายรับ (บาท)</label>
                <input type="number" step="0.01" class="form-control" id="income" name="income"
                    value="<?php echo htmlspecialchars($finance['income']); ?>">
            </div>
            <div class="mb-3">
                <label for="expense" class="form-label">รายจ่าย (บาท)</label>
                <input type="number" step="0.01" class="form-control" id="expense" name="expense"
                    value="<?php echo htmlspecialchars($finance['expense']); ?>">
            </div>
            <div class="mb-3">
                <label for="note" class="form-label">หมายเหตุ</label>
                <textarea class="form-control" id="note" name="note" rows="3"><?php echo htmlspecialchars($finance['note']); ?></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50">บันทึกการแก้ไข</button>
        </div>
    </form>
</div>