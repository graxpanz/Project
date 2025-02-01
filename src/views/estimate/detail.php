<div class="content-wrapper pt-3">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header border-0 pt-4">
                            <h4>
                                <i class="fas fa-user-circle"></i>
                                ข้อมูลการประเมินใบหน้า
                            </h4>
                            <a href="/estimate" class="btn btn-info mt-3">
                                <i class="fas fa-list"></i>
                                กลับหน้าหลัก
                            </a>
                        </div>

                        <form id="formData" action="/estimate/update" method="POST" class="p-4">
                            <input type="hidden" name="estimate_id" value="<?php echo htmlspecialchars($estimate['estimate_id']); ?>">

                            <div class="mb-4">
                                <label class="form-label fw-bold">ชื่อลูกค้า</label>
                                <p class="form-control-plaintext border-bottom">
                                    <?php echo htmlspecialchars($estimate['name']); ?>
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">รูปประเมินใบหน้า</label>
                                <?php if (!empty($estimate['file'])): ?>
                                    <?php
                                    $file_extension = pathinfo($estimate['file'], PATHINFO_EXTENSION);
                                    $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                                    ?>

                                    <?php if (in_array(strtolower($file_extension), $image_extensions)): ?>
                                        <div class="mt-2">
                                            <img src="/uploads/estimates/<?php echo htmlspecialchars($estimate['file']); ?>"
                                                alt="รูปประเมิน"
                                                class="img-fluid rounded"
                                                style="max-width: 300px;">
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            ไฟล์ไม่ใช่รูปภาพ
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        ไม่มีไฟล์แนบ
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">รายละเอียด</label>
                                <div class="form-control-plaintext border-bottom">
                                    <?php echo nl2br(htmlspecialchars($estimate['detail'])); ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="response" class="form-label fw-bold">การตอบกลับ</label>
                                <textarea class="form-control"
                                    id="response"
                                    name="response"
                                    rows="4"
                                    placeholder="กรุณากรอกการตอบกลับ..."><?php echo htmlspecialchars($estimate['response']); ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="price" class="form-label fw-bold">ราคาที่ประเมิน</label>
                                <input type="text"
                                    class="form-control"
                                    id="price"
                                    name="price"
                                    value="<?php echo htmlspecialchars($estimate['price']); ?>"
                                    placeholder="กรุณากรอกราคา...">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="date" class="form-label fw-bold">วันที่ประเมิน</label>
                                    <input type="date"
                                        class="form-control"
                                        id="date"
                                        name="date"
                                        value="<?php echo htmlspecialchars($estimate['date']); ?>"
                                        required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="status" class="form-label fw-bold">สถานะ</label>
                                    <select class="form-select form-control" id="status" name="status" required>
                                        <option value="Pending" <?php echo $estimate['status'] == 'ตอบกลับแล้ว' ? 'selected' : ''; ?>>
                                            ยังไม่ได้ตอบกลับ
                                        </option>
                                        <option value="Completed" <?php echo $estimate['status'] == 'ยังไม่ได้ตอบกลับ' ? 'selected' : ''; ?>>
                                            ตอบกลับแล้ว
                                        </option>
                                    </select>
                                    <small class="text-muted mt-1 d-block">
                                        เลือกสถานะการตอบกลับลูกค้า
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent text-center border-0">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save me-2"></i>
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>