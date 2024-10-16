<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-users"></i>
            จัดการข้อมูลลูกค้า
        </h4>
        <a href="/customer/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลลูกค้า
        </a>
    </div>
    <div class="card-body">
        <table id="logs" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>Email</th>
                    <th>เบอร์ติดต่อ</th>
                    <th>อายุ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($customers as $index => $customer): ?>
                    <tr>
                        <td><?php echo $index + 1 ; ?></td>
                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['surname']); ?></td>
                        <td><?php echo htmlspecialchars($customer['email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                        <td><?php echo htmlspecialchars($customer['age']); ?></td>
                        <td>
                            <a href="/customer/edit/<?php echo $customer['customer_id']; ?>" type="button"
                                class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <button type='button' class='btn btn-danger delete-btn'
                                data-id='<?php echo $customer['customer_id']; ?>'>
                                <i class='far fa-trash-alt'></i> ลบ
                            </button>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>


<script>
    // แสดงผลการทำงานสำเร็จด้วย sweet alert
    if (window.location.search.includes('?delete=success')) {
        Swal.fire("รายการของคุณถูกลบเรียบร้อย", "", "success");
        history.replaceState(null, null, window.location.pathname);
    }
    $(function () {
        $('#logs').DataTable({
            initComplete: function () {
                $(document).on('click', '.delete-btn', function () {
                    let customer_id = $(this).data('id');
                    Swal.fire({
                        text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ใช่',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/customer/delete/' + empId;
                        }
                    });
                }).on('change', '.toggle-event', function () {
                    toastr.success('อัพเดทข้อมูลเสร็จเรียบร้อย');
                });
            },
            fnDrawCallback: function () {
                $('.toggle-event').bootstrapToggle();
            },
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
            language: {
                "lengthMenu": "แสดงข้อมูล _MENU_ แถว",
                "zeroRecords": "ไม่พบข้อมูลที่ต้องการ",
                "info": "แสดงหน้า _PAGE_ จาก _PAGES_",
                "infoEmpty": "ไม่พบข้อมูลที่ต้องการ",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search": 'ค้นหา'
            }
        });
    });

</script>
</>

</html>