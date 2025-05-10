<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-list"></i>
            แก้ไขข้อมูลประเภทของบริการ
        </h4>
        <a href="/service_type" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <div class="card-body">
        <form action="/service_type/update" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="service_type_id" value="<?= htmlspecialchars($service_type['service_type_id']); ?>">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">ชื่อประเภทบริการ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= $service_type['name'] ? htmlspecialchars($service_type['name']) : ''; ?>" required>
                        <div class="invalid-feedback">
                        กรุณากรอกชื่อประเภทบริการ
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="description" class="form-label">รายละเอียด</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= $service_type['description'] ? htmlspecialchars($service_type['description']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                        <?= $service_type['is_active'] == '1' ? 'checked' : ''; ?>>
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