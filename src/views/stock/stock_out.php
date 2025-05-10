<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-minus-circle"></i>
            เบิกออกสต็อก
        </h4>
        <a href="/stock" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าจัดการคลัง
        </a>
    </div>
    <form action="/stock/insert" method="POST">
        <input type="hidden" name="movement_type" value="out">
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
                        <label for="movement_date">วันที่เบิกออก <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="movement_date" id="movement_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col col-md-6 px-1 px-md-5">
                    <div class="form-group">
                        <label for="reference">เลขที่อ้างอิง</label>
                        <input type="text" class="form-control" name="reference" id="reference" placeholder="เช่น รหัสการบริการ, รหัสพนักงาน">
                    </div>
                    <div class="form-group">
                        <label for="notes">หมายเหตุ</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="ระบุผู้เบิก หรือวัตถุประสงค์ในการเบิก"></textarea>
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
            <button type="submit" class="btn btn-danger btn-block mx-auto w-50" name="submit" id="submit-btn">
                <i class="fas fa-minus-circle"></i> บันทึกการเบิกออก
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
                const quantity = Number($('#quantity').val()) || 1;
                const newStock = currentStock - quantity;
                
                $('#stock-info').html(`
                    <strong>จำนวนคงเหลือปัจจุบัน:</strong> ${Number(currentStock).toLocaleString()} ${unit}<br>
                    <strong>หลังเบิกออก:</strong> <span id="new-stock-amount" class="${newStock < 0 ? 'text-danger' : ''}">${Number(newStock).toLocaleString()}</span> ${unit}
                    ${newStock < 0 ? '<br><div class="text-danger font-weight-bold">คำเตือน: จำนวนเบิกมากกว่าจำนวนคงเหลือ</div>' : ''}
                `);
                
                // ปิดปุ่มเมื่อสต็อกไม่พอ
                $('#submit-btn').prop('disabled', newStock < 0);
            } else {
                $('#stock-info').text('กรุณาเลือกวัสดุสิ้นเปลือง');
                $('#submit-btn').prop('disabled', false);
            }
        });
        
        // อัพเดทค่าสต็อกใหม่เมื่อเปลี่ยนจำนวน
        $('#quantity').on('input', function() {
            const selectedOption = $('#supply_id').find('option:selected');
            if (selectedOption.val()) {
                const currentStock = Number(selectedOption.data('stock'));
                const quantity = Number($(this).val()) || 0;
                const newStock = currentStock - quantity;
                
                $('#new-stock-amount')
                    .text(newStock.toLocaleString())
                    .toggleClass('text-danger', newStock < 0);
                
                // เพิ่มหรือลบข้อความเตือน
                if (newStock < 0) {
                    if ($('#stock-warning').length === 0) {
                        $('#stock-info').append('<div id="stock-warning" class="text-danger font-weight-bold">คำเตือน: จำนวนเบิกมากกว่าจำนวนคงเหลือ</div>');
                    }
                    $('#submit-btn').prop('disabled', true);
                } else {
                    $('#stock-warning').remove();
                    $('#submit-btn').prop('disabled', false);
                }
            }
        });
        
        // เรียกทริกเกอร์เมื่อโหลดหน้าถ้ามีการเลือกวัสดุแล้ว
        if ($('#supply_id').val()) {
            $('#supply_id').trigger('change');
        }
    });
</script>