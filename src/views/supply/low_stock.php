<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-exclamation-triangle text-warning"></i>
            วัสดุสิ้นเปลืองที่ใกล้หมด
        </h4>
        <div class="mt-3">
            <a href="/supply" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                กลับหน้าหลัก
            </a>
            <a href="/stock/stock-in" class="btn btn-success ml-2">
                <i class="fas fa-plus-circle"></i>
                รับเข้าสต็อก
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($supplies)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> ไม่พบวัสดุสิ้นเปลืองที่ใกล้หมด
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle"></i> พบวัสดุสิ้นเปลืองที่ใกล้หมดจำนวน <?= count($supplies) ?> รายการ
            </div>
            
            <table id="myTable" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th width="5%">ลำดับ</th>
                        <th width="10%">รูปภาพ</th>
                        <th width="20%">ชื่อ</th>
                        <th width="10%">หน่วย</th>
                        <th width="15%">จำนวนคงเหลือ</th>
                        <th width="15%">จำนวนขั้นต่ำ</th>
                        <th width="25%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($supplies as $index => $supply): ?>
                        <tr>
                            <td class=""><?= $index + 1 ?></td>
                            <td class="">
                                <img src="<?= !empty($supply['image']) ? '/assets/uploads/supplies/' . $supply['image'] : '/assets/images/no-image.jpg' ?>"
                                    alt="Supply"
                                    class="img-thumbnail"
                                    style="width: 70px; height: 70px; object-fit: cover;">
                            </td>
                            <td class="">
                                <?= htmlspecialchars($supply['name']) ?>
                            </td>
                            <td class="">
                                <?= htmlspecialchars($supply['unit']) ?>
                            </td>
                            <td class="text-danger font-weight-bold">
                                <?= number_format($supply['current_stock']) ?> <?= htmlspecialchars($supply['unit']) ?>
                                <span class="badge badge-danger">ใกล้หมด</span>
                            </td>
                            <td class="">
                                <?= number_format($supply['min_quantity']) ?> <?= htmlspecialchars($supply['unit']) ?>
                            </td>
                            <td class="">
                                <a href="/supply/view/<?= $supply['supply_id'] ?>" class="btn btn-info btn-sm mb-1">
                                    <i class="far fa-eye"></i> ดูข้อมูล
                                </a>
                                <a href="/stock/stock-in?supply_id=<?= $supply['supply_id'] ?>" class="btn btn-success btn-sm mb-1">
                                    <i class="fas fa-plus-circle"></i> รับเข้า
                                </a>
                                <a href="/supply/edit/<?= $supply['supply_id'] ?>" class="btn btn-warning text-white btn-sm mb-1">
                                    <i class="far fa-edit"></i> แก้ไข
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
    $(function() {
        // Initialize DataTable
        $('#myTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [
                [4, 'asc']
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
    });
</script>