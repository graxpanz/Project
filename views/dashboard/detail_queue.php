<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header border-0 pt-4">
                <h4>
                    <i class="fas fa-info-circle"></i> รายละเอียดการจอง
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($queue) && $queue): ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>รหัสการจอง</th>
                            <td><?php echo htmlspecialchars($queue['queue_id']); ?></td>
                        </tr>
                        <tr>
                            <th>ชื่อ-นามสกุล</th>
                            <td><?php echo htmlspecialchars($queue['name'] . ' ' . $queue['surname']); ?></td>
                        </tr>
                        <tr>
                            <th>เบอร์โทรศัพท์</th>
                            <td><?php echo htmlspecialchars($queue['phone']); ?></td>
                        </tr>
                        <tr>
                            <th>ประเภทบริการ</th>
                            <td><?php echo htmlspecialchars($queue['service_type_name']); ?></td>
                        </tr>
                        <tr>
                            <th>บริการ</th>
                            <td><?php echo htmlspecialchars($queue['service_name']); ?></td>
                        </tr>
                        <tr>
                            <th>พนักงาน</th>
                            <td><?php echo htmlspecialchars($queue['employee_name']); ?></td>
                        </tr>
                        <tr>
                            <th>วันที่จอง</th>
                            <td><?php echo htmlspecialchars($queue['queue_date']); ?></td>
                        </tr>
                        <tr>
                            <th>เวลาที่จอง</th>
                            <td><?php echo htmlspecialchars($queue['queue_time']); ?></td>
                        </tr>
                        <tr>
                            <th>สถานะ</th>
                            <td><?php echo htmlspecialchars($queue['status']); ?></td>
                        </tr>
                    </table>
                    <div class="mt-3">
                        <a href="/dashboard" class="btn btn-secondary">กลับสู่หน้าหลัก</a>
                        <!-- Add more buttons for actions like edit, cancel, etc. -->
                    </div>
                <?php else: ?>
                    <p>ไม่พบข้อมูลการจอง</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>