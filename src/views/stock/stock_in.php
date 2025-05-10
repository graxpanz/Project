<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-plus-circle"></i>
            รับเข้าสต็อก
        </h4>
        <a href="/stock" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าจัดการคลัง
        </a>
    </div>
    <form action="/stock/insert" method="POST">
        <input type="hidden" name="movement_type" value="in">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group">
                        <label for="supply_id">วัสดุสิ้นเปลือง <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="supply_id" id="supply_id" required>
                            <option value="" disabled <?= !isset($_GET['supply_id']) ? 'selected' : '' ?>>เลือกวัสดุสิ้นเปลือง</option>
                            <?php foreach ($supplies as $supply): ?>
                                <option value="<?= $supply['supply_id'] ?>" 
                                    data-unit="<?= htmlspecialchars($supply['unit']) ?>"
                                    data-stock="<?= $supply['current_stock'] ?>"
                                    <?= (isset($_GET['supply_id']) && $_GET['supply_id'] == $supply['supply_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($supply['name']) ?> (คงเหลือ: <?= number_format($supply['current_stock']) ?> <?= htmlspecialchars($supply['unit']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="quantity">จำนวน <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="quantity" id="quantity" min="1" value="1" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="unit-label">หน่วย</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="movement_date">วันที่รับเข้า <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="movement_date" id="movement_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="reference">เลขที่อ้างอิง</label>
                        <input type="text" class="form-control" name="reference" id="reference" placeholder="เช่น เลขที่ใบส่งของ, ใบเสร็จ">
                    </div>
                    <div class="form-group">
                        <label for="notes">หมายเหตุ</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="ข้อมูลเพิ่มเติม"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="row mt-2">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> ข้อมูลสต็อก:
                        <div id="stock-info">กรุณาเลือกวัสดุสิ้นเปลือง</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success btn-block mx-auto w-50" name="submit">
                <i class="fas fa-plus-circle"></i> บันทึกการรับเข้า
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // ใช้ Select2 สำหรับ dropdown
        $('.select2').select2({
            width: '100%'
        });
        
        // อัพเดทข้อมูลหน่วยและสต็อกเมื่อเลือกวัสดุ
        $('#supply_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            const currentStock = selectedOption.data('stock');
            
            // อัพเดทป้ายหน่วย
            $('#unit-label').text(unit || 'หน่วย');
            
            // อัพเดทข้อมูลสต็อก
            if (selectedOption.val()) {
                $('#stock-info').html(`
                    <strong>จำนวนคงเหลือปัจจุบัน:</strong> ${Number(currentStock).toLocaleString()} ${unit}<br>
                    <strong>หลังรับเข้า:</strong> <span id="new-stock-amount">${Number(currentStock + 1).toLocaleString()}</span> ${unit}
                `);
            } else {
                $('#stock-info').text('กรุณาเลือกวัสดุสิ้นเปลือง');
            }
        });
        
        // อัพเดทค่าสต็อกใหม่เมื่อเปลี่ยนจำนวน
        $('#quantity').on('input', function() {
            const selectedOption = $('#supply_id').find('option:selected');
            if (selectedOption.val()) {
                const currentStock = Number(selectedOption.data('stock'));
                const quantity = Number($(this).val()) || 0;
                const newStock = currentStock + quantity;
                
                $('#new-stock-amount').text(newStock.toLocaleString());
            }
        });
        
        // เรียกทริกเกอร์เมื่อโหลดหน้าถ้ามีการเลือกวัสดุแล้ว
        if ($('#supply_id').val()) {
            $('#supply_id').trigger('change');
        }
    });
</script>