<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-plus-circle"></i>
            เพิ่มรายการรายรับ-รายจ่าย
        </h4>
        <a href="/finance" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/finance/insert" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 px-1 px-md-5">
                    <div class="form-group">
                        <label for="transaction_date">วันที่ทำรายการ</label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="ระบุรายละเอียดรายการ" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="income">รายรับ (บาท)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="income" name="income" min="0" step="0.01" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text">บาท</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="outcome">รายจ่าย (บาท)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="outcome" name="outcome" min="0" step="0.01" value="0">
                                <div class="input-group-append">
                                    <span class="input-group-text">บาท</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_active">สถานะการใช้งาน</label>
                        <select class="form-control" id="is_active" name="is_active" required>
                            <option value="1" selected>เปิดใช้งาน</option>
                            <option value="0">ปิดใช้งาน</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">บันทึกข้อมูล</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // แสดงเตือนเมื่อกรอกทั้งรายรับและรายจ่าย
        $('#income, #outcome').on('input', function() {
            var income = parseFloat($('#income').val()) || 0;
            var outcome = parseFloat($('#outcome').val()) || 0;
            
            if (income > 0 && outcome > 0) {
                Swal.fire({
                    title: 'คำเตือน',
                    text: 'โดยปกติรายการจะเป็นรายรับหรือรายจ่ายอย่างใดอย่างหนึ่ง คุณแน่ใจหรือไม่ที่จะบันทึกทั้งสองรายการ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, บันทึกทั้งคู่',
                    cancelButtonText: 'ไม่, แก้ไขใหม่'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        // ผู้ใช้เลือกแก้ไข: ล้างค่าที่กรอกล่าสุด
                        if ($(this).attr('id') === 'income') {
                            $('#income').val('0');
                        } else {
                            $('#outcome').val('0');
                        }
                    }
                });
            }
        });
    });
</script>