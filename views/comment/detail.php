<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-comments"></i>
            ข้อมูลการแสดงความคิดเห็นของลูกค้า
        </h4>
        <a href="/comment" class="btn btn-info mt-3">
            <i class="fas fa-list"></i>
            กลับหน้าหลัก
        </a>
    </div>

    <form action="/comment/update" method="POST">
        <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['comment_id']); ?>">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">ความคิดเห็นลูกค้า</label>
                <div class="form-control-plaintext">
                    <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="response" class="form-label">การตอบกลับ</label>
                <textarea class="form-control" id="response" name="response" rows="3"
                    required><?php echo htmlspecialchars($comment['response']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">วันที่แสดงความคิดเห็น</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="<?php echo htmlspecialchars($comment['date']); ?>" required>
            </div>

            <hr>

            <!-- <div class="row mt-4">
                <div class="col">
                    <p class="text-muted mb-0">วันที่สร้าง: <?php echo htmlspecialchars($comment['created_at']); ?></p>
                    <?php if ($comment['updated_at']): ?>
                        <p class="text-muted mb-0">อัปเดตล่าสุด: <?php echo htmlspecialchars($comment['updated_at']); ?></p>
                    <?php endif; ?>
                </div>
            </div> -->
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block mx-auto w-50">บันทึกการตอบกลับ</button>
        </div>
    </form>
</div>