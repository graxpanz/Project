<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            จัดการข้อมูลพนักงาน
        </h4>
        <a href="/employee/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลพนักงาน
        </a>
    </div>
    <div class="card-body">
        <table id="employeeTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>วัน/เดือน/ปีเกิด</th>
                    <th>อายุ</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>อีเมล</th>
                    <th>ที่อยู่</th>
                    <th>ตำแหน่งการบริการ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $index => $emp): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($emp['fname']); ?></td>
                        <td><?php echo htmlspecialchars($emp['lname']); ?></td>
                        <td><?php echo htmlspecialchars($emp['bdate']); ?></td>
                        <td><?php echo htmlspecialchars($emp['age']); ?></td>
                        <td><?php echo htmlspecialchars($emp['tel']); ?></td>
                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                        <td><?php echo htmlspecialchars($emp['address']); ?></td>
                        <td><?php echo htmlspecialchars($emp['position_name']); ?></td>
                        <td>
                            <a href="/employee/edit/<?php echo $emp['emp_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <button type="button" class="btn btn-danger delete-btn" data-id="<?php echo $emp['emp_id']; ?>">
                                <i class="far fa-trash-alt"></i> ลบ
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function() {
        $('#employeeTable').DataTable({
            'responsive': true,
            'autoWidth': false,
        });

        $('.delete-btn').on('click', function() {
            let empId = $(this).data('id');
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'คุณจะไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/employee/delete/' + empId;
                }
            })
        });
    });
</script>