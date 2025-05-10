<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            แก้ไขข้อมูลลูกค้า
        </h4>
        <a href="/customer" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <form action="/customer/update" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer['customer_id']); ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="firstname" name="firstname"
                            value="<?= $customer['firstname'] ? htmlspecialchars($customer['firstname']) : ''; ?>" required>
                        <div class="invalid-feedback">
                            กรุณากรอกชื่อ
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lastname" class="form-label">นามสกุล</label>
                        <input type="text" class="form-control" id="lastname" name="lastname"
                            value="<?= $customer['lastname'] ? htmlspecialchars($customer['lastname']) : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= $customer['email'] ? htmlspecialchars($customer['email']) : ''; ?>" required>
                        <div class="invalid-feedback">
                            กรุณากรอกอีเมลให้ถูกต้อง
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?= $customer['phone'] ? htmlspecialchars($customer['phone']) : ''; ?>" pattern="[0-9]{10}">
                        <div class="invalid-feedback">
                            กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="birthdate" class="form-label">วัน/เดือน/ปีเกิด</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate"
                            value="<?= $customer['birthdate'] ? htmlspecialchars($customer['birthdate']) : ''; ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?= $customer['address'] ? htmlspecialchars($customer['address']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        <?= $customer['is_active'] == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">
                        เปิดใช้งาน
                    </label>
                </div>
            </div>

            <hr>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Form validation
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>