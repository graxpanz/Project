<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-box"></i>
            จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
        </h4>
        <a href="add_stock.php" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน
        </a>
        <a href="manage_add_stock.php" class="btn btn-success mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มจำนวนสินค้าและผลิตภัณฑ์ภายในร้าน
        </a>
        <a href="manage_use_stock.php" class="btn btn-danger mt-3">
            <i class="fas fa-minus"></i>
            บันทึกการใช้สินค้าและการขายผลิตภัณฑ์ภายในร้าน
        </a>
        <a href="order_stock.php" class="btn btn-warning mt-3">
            <i class="fas fa-file-alt"></i> ออกใบสั่งซื้อสินค้า
        </a>

        <form method="GET" class="mt-3">
            <label for="service_type_id">เลือกประเภทสินค้าการบริการ : </label>
            <select name="service_type_id" id="service_type_id" onchange="this.form.submit()">
                <option value="">ทั้งหมด</option>
                <?php foreach ($serviceTypes as $type): ?>
                    <option value="<?php echo $type['service_type_id']; ?>" <?php echo isset($_GET['service_type_id']) && $_GET['service_type_id'] == $type['service_type_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type['service_type_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="card-body">
        <table id="logs" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อสินค้า</th>
                    <th>ประเภทสินค้าการบริการ</th>
                    <th>จำนวน</th>
                    <th>รูปสินค้า</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($stock as $index => $stock): 
                $filteredStocks = isset($_GET['service_type_id']) && $_GET['service_type_id'] != ''
                    ? array_filter($stocks, function ($stock) {
                        return $stock['service_type_id'] == $_GET['service_type_id'];
                    })
                    : $stock;
                    ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($stock['stock_name']); ?></td>
                        <td><?php echo htmlspecialchars($stock['service_type_name']); ?></td>
                        <td><?php echo htmlspecialchars($stock['amount']); ?></td>
                        <td><img src="../stock/image/<?php echo htmlspecialchars($stock['image']); ?>" alt="Stock Image"
                                style="width: 50px; height: 50px;"></td>
                        <td>
                            <a href="edit_stock.php?id=<?php echo $stock['stock_id']; ?>" type="button"
                                class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <button type='button' class='btn btn-danger delete-btn'
                                data-id='<?php echo $stock['stock_id']; ?>'>
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
    if (window.location.search.includes('?delete=success')) {
        Swal.fire("รายการของคุณถูกลบเรียบร้อย", "", "success");
        history.replaceState(null, null, window.location.pathname);
    }
    $(function () {
        $('#logs').DataTable({
            initComplete: function () {
                $(document).on('click', '.delete-btn', function () {
                    let stock_id = $(this).data('id');
                    Swal.fire({
                        text: "คุณต้องการลบข้อมูลนี้ใช่หรือไม่",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ใช่',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `delete_stock.php?id=${stock_id}`;
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
</body>

</html>