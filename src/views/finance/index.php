<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-wallet"></i>
            จัดการข้อมูลการเงิน
        </h4>
        <a href="/finance/add" class="btn btn-primary mt-3">
            <i class="fas fa-plus"></i>
            เพิ่มข้อมูลการเงิน
        </a>
    </div>
    
    <!-- สรุปยอดรวม -->
    <div class="row mx-3 mt-3">
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo number_format($summary['total_income'], 2); ?> บาท</h3>
                    <p>รายรับทั้งหมด</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo number_format($summary['total_expense'], 2); ?> บาท</h3>
                    <p>รายจ่ายทั้งหมด</p>
                </div>
                <div class="icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo number_format($summary['net_total'], 2); ?> บาท</h3>
                    <p>ยอดคงเหลือ</p>
                </div>
                <div class="icon">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table id="financeTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>รายรับ</th>
                    <th>รายจ่าย</th>
                    <th>คงเหลือ</th>
                    <th>หมายเหตุ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($finances as $finance): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($finance['date']); ?></td>
                        <td><?php echo htmlspecialchars($finance['list']); ?></td>
                        <td class="text-success"><?php echo number_format($finance['income'], 2); ?></td>
                        <td class="text-danger"><?php echo number_format($finance['expense'], 2); ?></td>
                        <td><?php echo number_format($finance['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($finance['note']); ?></td>
                        <td>
                            <a href="/finance/edit/<?php echo $finance['finance_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/finance/delete/<?php echo $finance['finance_id']; ?>" method="POST" class="d-inline">
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
        $('#financeTable').DataTable({
            'responsive': true,
            'autoWidth': false,
            'order': [[0, 'desc']], // เรียงตามวันที่ล่าสุด
            'language': {
                'lengthMenu': 'แสดงข้อมูล _MENU_ แถว',
                'zeroRecords': 'ไม่พบข้อมูลที่ต้องการ',
                'info': 'แสดงหน้า _PAGE_ จาก _PAGES_',
                'infoEmpty': 'ไม่พบข้อมูลที่ต้องการ',
                'infoFiltered': '(filtered from _MAX_ total records)',
                'search': 'ค้นหา'
            }
        });

        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
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
                    form.submit();
                }
            });
        });
    });
</script>