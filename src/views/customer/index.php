<?php
$title = 'จัดการข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร';
?>

<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            จัดการข้อมูลลูกค้า
        </h4>
        <a href="/customer/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูล
        </a>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="20%">ชื่อ-นามสกุล</th>
                    <th width="25%">ข้อมูลติดต่อ</th>
                    <th width="25%">ที่อยู่</th>
                    <th width="10%">สถานะ</th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $index => $customer): ?>
                    <tr>
                        <td class="text-center align-middle"><?= $index + 1 ?></td>
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold">
                                    <?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex flex-column">
                                <span><i class="fas fa-envelope mr-2"></i><?= $customer['email'] ? htmlspecialchars($customer['email']) : 'ไม่มีข้อมูลอีเมล' ?></span>
                                <span><i class="fas fa-phone mr-2"></i><?= $customer['phone'] ? htmlspecialchars($customer['phone']) : 'ไม่มีข้อมูลเบอร์โทรศัพท์' ?></span>
                            </div>
                        </td>
                        <td class="align-middle">
                            <?= $customer['address'] ? htmlspecialchars($customer['address']) : 'ไม่มีข้อมูลที่อยู่' ?>
                        </td>
                        <td class="align-middle">
                            <?php if ($customer['is_active'] == '1'): ?>
                                <span class="badge badge-success">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="badge badge-danger">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center align-middle">
                            <a href="/customer/edit/<?= $customer['customer_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/customer/delete/<?= $customer['customer_id']; ?>" method="POST" class="d-inline">
                                <button type="button" class="btn btn-danger delete-btn">
                                    <i class="far fa-trash-alt"></i> ลบ
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(function() {
        // Initialize DataTable
        $('#myTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [
                [0, 'asc']
            ],
            'language': {
                'lengthMenu': 'แสดงข้อมูล _MENU_ แถว',
                'zeroRecords': 'ไม่พบข้อมูลที่ต้องการ',
                'info': 'แสดงหน้า _PAGE_ จาก _PAGES_',
                'infoEmpty': 'ไม่พบข้อมูลที่ต้องการ',
                'infoFiltered': '(กรองจากทั้งหมด _MAX_ รายการ)',
                'search': 'ค้นหา:',
                'paginate': {
                    'first': 'หน้าแรก',
                    'last': 'หน้าสุดท้าย',
                    'next': 'ถัดไป',
                    'previous': 'ก่อนหน้า'
                }
            }
        });

        // Delete confirmation
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'การดำเนินการนี้ไม่สามารถย้อนกลับได้!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, ลบข้อมูล!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Toggle status
        $('.toggle-status').on('click', function() {
            const id = $(this).data('id');
            const newStatus = $(this).data('status');
            const statusText = newStatus === 1 ? 'เปิด' : 'ปิด';

            Swal.fire({
                title: 'ยืนยันการเปลี่ยนแปลง?',
                text: `คุณต้องการ${statusText}การใช้งานลูกค้านี้หรือไม่?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'ใช่, เปลี่ยนแปลง!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/customer/toggle-status/${id}`, {
                            status: newStatus
                        })
                        .done(function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: 'เปลี่ยนแปลงสถานะเรียบร้อยแล้ว',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด!',
                                text: 'ไม่สามารถเปลี่ยนแปลงสถานะได้'
                            });
                        });
                }
            });
        });
    });
</script>