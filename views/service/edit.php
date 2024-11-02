<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-air-freshener"></i>
            แก้ไขข้อมูลการบริการ
        </h4>
        <a href="/service" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/service/update" method="POST">
        <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['service_id']); ?>">
        <div class="card-body">
            <div class="mb-3">
                <label for="service_name" class="form-label">ชื่อการบริการ</label>
                <input type="text" class="form-control" id="service_name" name="service_name"
                    value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="service_type_id" class="form-label">ประเภทการบริการ</label>
                <select class="form-control" id="service_type_id" name="service_type_id" required>
                    <?php foreach ($serviceTypes as $type): ?>
                        <option value="<?php echo $type['service_type_id']; ?>"
                            <?php echo $service['service_type_id'] == $type['service_type_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($type['service_type_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="service_price" class="form-label">ราคา(บาท)</label>
                <input type="number" class="form-control" id="service_price" name="service_price"
                    value="<?php echo htmlspecialchars($service['service_price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="service_time" class="form-label">เวลาในการบริการ(นาที)</label>
                <input type="number" class="form-control" id="service_time" name="service_time"
                    value="<?php echo htmlspecialchars($service['service_time']); ?>" required>
                <div id="service_time_help" class="form-text">
                    กรุณากรอกจำนวนเวลาในการให้บริการเป็นนาทีเท่านั้น
                </div>
            </div>
            <div class="mb-3">
                <label for="service_detail" class="form-label">รายละเอียด</label>
                <input type="text" class="form-control" id="service_detail" name="service_detail"
                    value="<?php echo htmlspecialchars($service['service_detail']); ?>" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกการแก้ไข</button>
        </div>
    </form>
</div>