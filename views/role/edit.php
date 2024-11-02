<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-tag"></i>
            แก้ไขสิทธ์การใช้งาน
        </h4>
        <a href="/role" class="btn btn-info my-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>
    <form action="/role/update" method="POST">
        <input type="hidden" name="role_id" value="<?= $role['role_id'] ?>">
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อตำแหน่ง</label>
                <input type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= $role['name'] ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="detail" class="form-label">สิทธ์การเข้าถึง</label>
                <div>
                    <?php
                    $currentPermissions = explode(", ", $role['permission']);
                    foreach ($permissions as $key => $permission) {
                        $isChecked = in_array($key, $currentPermissions) ? 'checked' : '';
                    ?>
                        <div class="form-check">
                            <input class="form-check-input"
                                name="permissions[]"
                                type="checkbox"
                                id="<?= $key ?>"
                                value="<?= $key ?>"
                                <?= $isChecked ?>>
                            <label class="form-check-label" for="<?= $key ?>">
                                <?= $permission ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="mb-3 col-md-6 col-lg-4 col-xl-3">
                <label for="is_active" class="form-label">สถานะ</label>
                <select class="custom-select" name="is_active" id="is_active">
                    <option value="0" <?= $role['is_active'] == 0 ? 'selected' : '' ?>>
                        ปิดใช้งาน
                    </option>
                    <option value="1" <?= $role['is_active'] == 1 ? 'selected' : '' ?>>
                        เปิดใช้งาน
                    </option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50" name="submit">
                บันทึกข้อมูล
            </button>
        </div>
    </form>
</div>