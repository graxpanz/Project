<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header border-0 pt-4">
                <h4>
                    <i class="fas fa-address-book"></i> สถานะการจองคิว
                </h4>
            </div>
            <div class="row">
                <div class="col-lg-3 col-6 mb-4">
                    <div class="small-box bg-warning shadow" style="color: #fff !important;">
                        <div class="inner text-center">
                            <h4 class="py-3">รอดำเนินการ</h4>
                            <h1><?php echo $pendingCount; ?></h1>
                            <p>จำนวนการจองที่รอดำเนินการ</p>
                        </div>
                        <a href="#" class="small-box-footer py-3" style="color: #fff !important;"> รายละเอียด
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <div class="small-box bg-primary shadow">
                        <div class="inner text-center">
                            <h4 class="py-3">ยืนยันแล้ว</h4>
                            <h1><?php echo $confirmedCount; ?></h1>
                            <p>จำนวนการจองที่ยืนยันแล้ว</p>
                        </div>
                        <a href="#" class="small-box-footer py-3"> รายละเอียด
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <div class="small-box bg-success shadow">
                        <div class="inner text-center">
                            <h4 class="py-3">เสร็จสิ้น</h4>
                            <h1><?php echo $completedCount; ?></h1>
                            <p>จำนวนการจองที่เสร็จสิ้น</p>
                        </div>
                        <a href="#" class="small-box-footer py-3"> รายละเอียด
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <div class="small-box bg-danger shadow">
                        <div class="inner text-center">
                            <h4 class="py-3">ยกเลิก</h4>
                            <h1><?php echo $cancelledCount; ?></h1>
                            <p>จำนวนการจองที่ยกเลิก</p>
                        </div>
                        <a href="#" class="small-box-footer py-3"> รายละเอียด
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-header border-0 pt-4">
                <h4>
                    <i class="fas fa-list-ul"></i>
                    รายการจองคิวทั้งหมด
                </h4>
            </div>
            <div class="card-body">
                <table id="logs" class="table table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>เบอร์ติดต่อ</th>
                            <th>ประเภทการบริการ</th>
                            <th>การบริการ</th>
                            <th>พนักงาน</th>
                            <th>วันที่จอง</th>
                            <th>เวลาที่จอง</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $num = 0;
                        foreach ($queues as $queue):
                            $num++;
                            ?>
                            <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo htmlspecialchars($queue['name']); ?></td>
                                <td><?php echo htmlspecialchars($queue['surname']); ?></td>
                                <td><?php echo htmlspecialchars($queue['phone']); ?></td>
                                <td><?php echo htmlspecialchars($queue['service_type_name']); ?></td>
                                <td><?php echo htmlspecialchars($queue['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($queue['fname']); ?></td>
                                <td><?php echo htmlspecialchars($queue['queue_date']); ?></td>
                                <td><?php echo htmlspecialchars($queue['queue_time']); ?></td>
                                <td><?php echo htmlspecialchars($queue['status']); ?></td>
                                <td>
                                <a href="/dashboard/detail-queue/<?php echo htmlspecialchars($queue['queue_id']); ?>" class="btn btn-info">
                                    <i class="far fa-edit"></i> รายละเอียด
                                </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('#logs').DataTable({
            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
            language: {
                'lengthMenu': 'แสดงข้อมูล _MENU_ แถว',
                'zeroRecords': 'ไม่พบข้อมูลที่ต้องการ',
                'info': 'แสดงหน้า _PAGE_ จาก _PAGES_',
                'infoEmpty': 'ไม่พบข้อมูลที่ต้องการ',
                'infoFiltered': '(filtered from _MAX_ total records)',
                'search': 'ค้นหา'
            }
        });
    });
</script>