<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-box"></i>
            เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
        </h4>
        <a href="./" class="btn btn-info mt-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>

    <form id="formData" action="update_stock.php" method="post" style="padding: 20px;">
        <input type="hidden" name="stock_id" value="<?php echo htmlspecialchars($stock['stock_id']); ?>">
        <div class="mb-3">
            <label for="stock_name" class="form-label">ชื่อสินค้า</label>
            <input type="text" class="form-control" id="stock_name" name="stock_name"
                value="<?php echo htmlspecialchars($stock['stock_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="service_type_id" class="form-label">ประเภทสินค้าการบริการ</label>
            <select class="form-control" id="service_type_id" name="service_type_id">
                <?php
                $sql = "SELECT * FROM service_type ORDER BY service_type_name";
                $stmt = $conn->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($row['service_type_id'] == $stock['service_type_id']) ? 'selected' : '';
                    echo "<option value='{$row['service_type_id']}' $selected>{$row['service_type_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">จำนวนสินค้า</label>
            <input type="number" class="form-control" id="amount" name="amount"
                value="<?php echo htmlspecialchars($stock['amount']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">รูปภาพสินค้า</label>
            <input type="file" class="form-control-file" id="image" name="image">
            <?php if (!empty($stock['image'])): ?>
                <img src="image/<?php echo htmlspecialchars($stock['image']); ?>" alt="Stock Image"
                    style="width: 50px; height: 50px;">
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>