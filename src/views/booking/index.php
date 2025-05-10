<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4>
                <i class="fas fa-calendar-check"></i>
                จัดการข้อมูลการจอง
            </h4>
            <div class="d-flex">
                <a href="/booking/add" class="btn btn-primary mx-1">
                    <i class="fas fa-plus"></i>
                    เพิ่มข้อมูล
                </a>
                <button type="button" class="btn btn-success mx-1" data-toggle="modal" data-target="#reportModal">
                    <i class="fas fa-file-alt"></i>
                    รายงานสรุป
                </button>
                <button type="button" class="btn btn-info mx-1" id="printBookingList">
                    <i class="fas fa-print"></i>
                    พิมพ์รายการ
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table id="myTable" class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="15%">บริการ</th>
                    <th width="15%">ลูกค้า</th>
                    <th width="12%">ผู้ให้บริการ</th>
                    <th width="10%">วันที่และเวลา</th>
                    <th width="8%">ราคา</th>
                    <th width="8%">โปรโมชั่น</th>
                    <th width="8%">สถานะ</th>
                    <th width="15%" class="no-print">การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr data-status="<?= $booking['status'] ?>">
                        <td class=""><?= $index + 1 ?></td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <div class="mb-2">
                                    <img src="<?= !empty($booking['service']['image']) ? '/assets/uploads/service/' . $booking['service']['image'] : '/assets/images/no-image.jpg' ?>"
                                        alt="Service"
                                        class="img-thumbnail"
                                        style="width: 80px; height: auto; object-fit: cover;">
                                </div>
                                <span><b><?= htmlspecialchars($booking['service']['name']) ?></b></span>
                                <small class="text-muted">ประเภท: <?= htmlspecialchars($booking['service']['type']['name']) ?></small>
                                <small class="text-muted">เวลา: <?= htmlspecialchars($booking['service']['time']) ?> นาที</small>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <span><?= htmlspecialchars($booking['customer']['firstname'] . ' ' . $booking['customer']['lastname']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($booking['customer']['email']) ?></small>
                                <small class="text-muted"><?= htmlspecialchars($booking['customer']['phone']) ?></small>
                            </div>
                        </td>
                        <td class="">
                            <div class="d-flex flex-column">
                                <?php if (!empty($booking['user'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= !empty($booking['user']['image']) ? '/assets/uploads/user/' . $booking['user']['image'] : '/assets/images/no-image.jpg' ?>"
                                            alt="User"
                                            class="img-thumbnail rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                    <span><?= htmlspecialchars($booking['user']['firstname'] . ' ' . $booking['user']['lastname']) ?></span>
                                    <small class="text-muted"><?= htmlspecialchars($booking['user']['role']['name']) ?></small>
                                <?php else: ?>
                                    <span class="text-muted">ยังไม่ได้กำหนด</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="">
                            <?= $this->dateFormat($booking['appointment_datetime']) ?>
                        </td>
                        <td class="">
                            <?= number_format($booking['total_price'], 2) ?> บาท
                        </td>
                        <td class="">
                            <?php if (!empty($booking['promotion']) && !empty($booking['promotion']['name'])): ?>
                                <div class="d-flex flex-column">
                                    <span class="badge badge-success"><?= htmlspecialchars($booking['promotion']['name']) ?></span>
                                    <small class="text-danger">ส่วนลด <?= htmlspecialchars($booking['promotion']['discount']) ?></small>
                                    <?php if (!empty($booking['promotion']['code'])): ?>
                                        <small class="text-muted">รหัส: <?= htmlspecialchars($booking['promotion']['code']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">ไม่มี</span>
                            <?php endif; ?>
                        </td>
                        <td class="">
                            <?php if ($booking['status'] == 'pending'): ?>
                                <span class="badge badge-warning">รอยืนยัน</span>
                            <?php elseif ($booking['status'] == 'confirm'): ?>
                                <span class="badge badge-primary">ยืนยันแล้ว</span>
                            <?php elseif ($booking['status'] == 'cancel'): ?>
                                <span class="badge badge-danger">ยกเลิก</span>
                            <?php elseif ($booking['status'] == 'complete'): ?>
                                <span class="badge badge-success">เสร็จสิ้น</span>
                            <?php endif; ?>
                        </td>
                        <td class="no-print">
                            <a href="/booking/edit/<?= $booking['booking_id']; ?>" class="btn btn-warning text-white">
                                <i class="far fa-edit"></i> แก้ไข
                            </a>
                            <form action="/booking/delete/<?= $booking['booking_id']; ?>" method="POST" class="d-inline mt-1">
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

<!-- Modal รายงานสรุป -->
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">รายงานสรุปการจองคิว</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- ตัวกรองวันที่สำหรับรายงาน -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-row align-items-end">
                            <div class="col-md-4 my-1">
                                <label for="reportStartDate">วันที่เริ่มต้น</label>
                                <input type="date" class="form-control" id="reportStartDate" value="<?= date('Y-m-01') ?>">
                            </div>
                            <div class="col-md-4 my-1">
                                <label for="reportEndDate">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" id="reportEndDate" value="<?= date('Y-m-t') ?>">
                            </div>
                            <div class="col-md-4 my-1 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" id="generateReport">
                                    <i class="fas fa-sync-alt"></i> สร้างรายงาน
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- สรุปข้อมูลการจองคิว -->
                <div id="bookingSummary" class="report-content">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">กำลังโหลด...</span>
                        </div>
                        <p class="mt-2">กำลังสร้างรายงาน...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-success" id="printReport">
                    <i class="fas fa-print"></i> พิมพ์รายงาน
                </button>
            </div>
        </div>
    </div>
</div>

<!-- สไตล์สำหรับการพิมพ์ -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        
        .no-print {
            display: none;
        }
        
        #myTable, #myTable * {
            visibility: visible;
        }
        
        #myTable {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        /* สำหรับพิมพ์รายงาน */
        .report-print-content, .report-print-content * {
            visibility: visible;
        }
        
        .report-print-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>

<script>
    $(function() {
        const bookings = <?= json_encode($bookings) ?>;
        const dataTable = $('#myTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[4, 'asc']], // เรียงตามวันที่นัดหมาย
            language: {
                lengthMenu: 'แสดงข้อมูล _MENU_ แถว',
                zeroRecords: 'ไม่พบข้อมูลที่ต้องการ',
                info: 'แสดงหน้า _PAGE_ จาก _PAGES_',
                infoEmpty: 'ไม่พบข้อมูลที่ต้องการ',
                infoFiltered: '(กรองจากทั้งหมด _MAX_ รายการ)',
                search: 'ค้นหา:',
                paginate: {
                    first: 'หน้าแรก',
                    last: 'หน้าสุดท้าย',
                    next: 'ถัดไป',
                    previous: 'ก่อนหน้า'
                }
            },
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    titleAttr: 'ส่งออกเป็น Excel',
                    className: 'btn btn-sm btn-success mr-1 d-none'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    titleAttr: 'ส่งออกเป็น PDF',
                    className: 'btn btn-sm btn-danger mr-1 d-none'
                }
            ]
        });
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
        

        $('#printBookingList').on('click', function() {
            // ปรับแต่งสไตล์สำหรับการพิมพ์
            $('body').addClass('printing-table');
            
            // เพิ่ม header สำหรับการพิมพ์
            const printHeader = `
                <div class="print-header text-center mb-4">
                    <h2>รายการการจองคิว</h2>
                    <h4>Mira ศูนย์ความงามครบวงจร</h4>
                    <p>วันที่พิมพ์: ${new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                </div>
            `;
            
            // เพิ่ม header ก่อนตาราง
            $('#myTable').before(printHeader);
            
            // พิมพ์
            window.print();
            
            // ลบ header หลังจากพิมพ์
            setTimeout(function() {
                $('.print-header').remove();
                $('body').removeClass('printing-table');
            }, 100);
        });
        
        
        $('#reportModal').on('shown.bs.modal', function() {
            // ตั้งค่าวันที่เริ่มต้นและวันที่สิ้นสุดเป็นเดือนปัจจุบัน
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            $('#reportStartDate').val(formatDateForInput(firstDay));
            $('#reportEndDate').val(formatDateForInput(lastDay));
            
            // สร้างรายงานเริ่มต้น
            generateBookingReport($('#reportStartDate').val(), $('#reportEndDate').val());
        });
        
        // เมื่อคลิกปุ่มสร้างรายงาน
        $('#generateReport').on('click', function() {
            const startDate = $('#reportStartDate').val();
            const endDate = $('#reportEndDate').val();
            
            // ตรวจสอบความถูกต้องของวันที่
            if (!startDate || !endDate) {
                Swal.fire({
                    title: 'ข้อมูลไม่ครบถ้วน',
                    text: 'กรุณาระบุวันที่เริ่มต้นและวันที่สิ้นสุด',
                    icon: 'warning'
                });
                return;
            }
            
            if (new Date(startDate) > new Date(endDate)) {
                Swal.fire({
                    title: 'วันที่ไม่ถูกต้อง',
                    text: 'วันที่เริ่มต้นต้องน้อยกว่าหรือเท่ากับวันที่สิ้นสุด',
                    icon: 'warning'
                });
                return;
            }
            
            // แสดงโหลดิ้ง
            $('#bookingSummary').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">กำลังโหลด...</span>
                    </div>
                    <p class="mt-2">กำลังสร้างรายงาน...</p>
                </div>
            `);
            
            // สร้างรายงาน
            setTimeout(() => {
                generateBookingReport(startDate, endDate);
            }, 500);
        });
        
        // เมื่อคลิกปุ่มพิมพ์รายงาน
        $('#printReport').on('click', function() {
            const reportContent = $('#bookingSummary').html();
            const reportTitle = 'รายงานสรุปการจองคิว';
            const startDate = $('#reportStartDate').val();
            const endDate = $('#reportEndDate').val();
            const dateRange = `ระหว่างวันที่ ${formatDate(startDate)} ถึง ${formatDate(endDate)}`;
            
            // เปิดหน้าต่างใหม่สำหรับพิมพ์
            const printWindow = window.open('', '_blank');
            
            // เตรียม HTML
            let htmlContent = '<!DOCTYPE html><html><head>';
            htmlContent += '<title>&nbsp;</title>';
            htmlContent += '<meta charset="utf-8">';
            htmlContent += '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">';
            htmlContent += '<style>';
            htmlContent += '@import url("https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap");';
            htmlContent += 'body { font-family: "Sarabun", sans-serif; padding: 20px; color: #000; }';
            htmlContent += '.report-header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #ddd; color: #000; }';
            htmlContent += '.card { margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px; }';
            htmlContent += '.card-header { background-color: #f8f9fa; border-bottom: 1px solid #ddd; padding: 10px 15px; color: #000; }';
            htmlContent += '.card-body { padding: 15px; color: #000; }';
            htmlContent += '.table { width: 100%; margin-bottom: 1rem; color: #000; border-collapse: collapse; }';
            htmlContent += '.table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; color: #000; }';
            htmlContent += '.table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; color: #000; }';
            htmlContent += '.table-bordered { border: 1px solid #dee2e6; }';
            htmlContent += '.table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }';
            htmlContent += '.text-right { text-align: right; }';
            htmlContent += '.text-center { text-align: center; }';
            
            // ปรับให้ background-color ของ .bg-primary ยังคงอยู่ แต่ตัวอักษรเป็นสีดำ
            htmlContent += '.bg-primary { background-color: #f8f9fa !important; color: #000 !important; }';
            
            // แทนที่ .text-white ด้วยสีดำ
            htmlContent += '.text-white { color: #000 !important; }';
            
            // กำหนดให้ทุก badge มีสีพื้นหลังเดิม แต่ตัวอักษรเป็นสีดำ
            htmlContent += '.badge { color: #000 !important; }';
            htmlContent += '.badge-warning { background-color: #ffc107; color: #000 !important; }';
            htmlContent += '.badge-primary { background-color: #b8daff; color: #000 !important; }';
            htmlContent += '.badge-success { background-color: #c3e6cb; color: #000 !important; }';
            htmlContent += '.badge-danger { background-color: #f5c6cb; color: #000 !important; }';
            
            // กำหนดสีสำหรับส่วนอื่นๆ
            htmlContent += 'h1, h2, h3, h4, h5, h6, p, span, div { color: #000; }';
            htmlContent += 'label, small, strong, b { color: #000; }';
            
            htmlContent += '@media print { @page { size: A4; margin: 1cm; } .card { break-inside: avoid; } body, * { color: #000 !important; } }';
            htmlContent += '</style>';
            htmlContent += '</head><body>';
            htmlContent += '<div class="report-print-content">';
            htmlContent += '<div class="report-header">';
            htmlContent += '<h2>' + reportTitle + '</h2>';
            htmlContent += '<h4>Mira ศูนย์ความงามครบวงจร</h4>';
            htmlContent += '<p>' + dateRange + '</p>';
            htmlContent += '<p>วันที่พิมพ์: ' + new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' }) + '</p>';
            htmlContent += '</div>';
            htmlContent += reportContent;
            htmlContent += '</div>';
            htmlContent += '<script>';
            htmlContent += 'window.onload = function() {';
            htmlContent += '  const allElements = document.querySelectorAll("*");';
            htmlContent += '  allElements.forEach(el => {';
            htmlContent += '    const style = window.getComputedStyle(el);';
            htmlContent += '    if (style.color !== "rgb(0, 0, 0)") {';
            htmlContent += '      el.style.color = "#000";';
            htmlContent += '    }';
            htmlContent += '  });';
            htmlContent += '  setTimeout(function() {';
            htmlContent += '    window.print();';
            htmlContent += '    setTimeout(function() { window.close(); }, 500);';
            htmlContent += '  }, 500);';
            htmlContent += '};';
            htmlContent += '<\/script>';
            htmlContent += '</body></html>';
            
            // เขียน HTML ลงในหน้าต่างที่เปิด
            printWindow.document.open();
            printWindow.document.write(htmlContent);
            printWindow.document.close();
        });
        

        function generateBookingReport(startDate, endDate) {
            // กรองข้อมูลตามช่วงวันที่
            const filteredBookings = bookings.filter(booking => {
                const bookingDate = new Date(booking.appointment_datetime).toISOString().split('T')[0];
                return bookingDate >= startDate && bookingDate <= endDate;
            });
            
            // สถิติพื้นฐาน
            const statusCounts = {
                pending: 0,
                confirm: 0,
                cancel: 0,
                complete: 0
            };
            
            // สถิติการเงิน
            let totalRevenue = 0;
            let totalDiscount = 0;
            
            // สถิติตามประเภทบริการ
            const serviceTypes = {};
            
            // สถิติตามพนักงาน
            const employees = {};
            
            // วิเคราะห์ข้อมูล
            filteredBookings.forEach(booking => {
                // นับตามสถานะ
                if (booking.status in statusCounts) {
                    statusCounts[booking.status]++;
                }
                
                // คำนวณรายได้ (เฉพาะสถานะ confirm และ complete)
                if (booking.status === 'confirm' || booking.status === 'complete') {
                    totalRevenue += parseFloat(booking.total_price || 0);
                    totalDiscount += parseFloat(booking.discount || 0);
                }
                
                // นับตามประเภทบริการ
                if (booking.service && booking.service.type && booking.service.type.name) {
                    const serviceType = booking.service.type.name;
                    if (!serviceTypes[serviceType]) {
                        serviceTypes[serviceType] = {
                            count: 0,
                            revenue: 0
                        };
                    }
                    
                    serviceTypes[serviceType].count++;
                    
                    if (booking.status === 'confirm' || booking.status === 'complete') {
                        serviceTypes[serviceType].revenue += parseFloat(booking.total_price || 0);
                    }
                }
                
                // นับตามพนักงาน
                if (booking.user && booking.user.firstname) {
                    const employeeName = `${booking.user.firstname} ${booking.user.lastname || ''}`.trim();
                    
                    if (!employees[employeeName]) {
                        employees[employeeName] = {
                            count: 0,
                            revenue: 0
                        };
                    }
                    
                    employees[employeeName].count++;
                    
                    if (booking.status === 'confirm' || booking.status === 'complete') {
                        employees[employeeName].revenue += parseFloat(booking.total_price || 0);
                    }
                }
            });
            
            // จัดเรียงข้อมูล
            const sortedServiceTypes = Object.entries(serviceTypes)
                .sort((a, b) => b[1].count - a[1].count)
                .map(([name, data]) => ({ name, ...data }));
                
            const sortedEmployees = Object.entries(employees)
                .sort((a, b) => b[1].count - a[1].count)
                .map(([name, data]) => ({ name, ...data }));
            
            // สร้าง HTML สำหรับรายงาน
            let reportHtml = `
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">ข้อมูลสรุปการจองคิว</h5>
                        <small>ระหว่างวันที่ ${formatDate(startDate)} ถึง ${formatDate(endDate)}</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">สถานะการจอง</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td><span class="badge badge-warning">รอยืนยัน</span></td>
                                                    <td class="text-right">${statusCounts.pending}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-primary">ยืนยันแล้ว</span></td>
                                                    <td class="text-right">${statusCounts.confirm}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-success">เสร็จสิ้น</span></td>
                                                    <td class="text-right">${statusCounts.complete}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-danger">ยกเลิก</span></td>
                                                    <td class="text-right">${statusCounts.cancel}</td>
                                                </tr>
                                                <tr class="font-weight-bold bg-light">
                                                    <td>รวมทั้งหมด</td>
                                                    <td class="text-right">${filteredBookings.length}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">สรุปรายได้</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>รายได้รวม (เฉพาะยืนยันและเสร็จสิ้น)</td>
                                                    <td class="text-right">${totalRevenue.toFixed(2)} บาท</td>
                                                </tr>
                                                <tr>
                                                    <td>ส่วนลดรวม</td>
                                                    <td class="text-right">${totalDiscount.toFixed(2)} บาท</td>
                                                </tr>
                                                <tr>
                                                    <td>จำนวนการจองที่มีรายได้</td>
                                                    <td class="text-right">${statusCounts.confirm + statusCounts.complete}</td>
                                                </tr>
                                                <tr class="font-weight-bold bg-light">
                                                    <td>รายได้เฉลี่ยต่อการจอง</td>
                                                    <td class="text-right">
                                                        ${(statusCounts.confirm + statusCounts.complete > 0 
                                                            ? (totalRevenue / (statusCounts.confirm + statusCounts.complete)).toFixed(2) 
                                                            : 0)} บาท
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">ประเภทบริการยอดนิยม</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ประเภทบริการ</th>
                                                    <th class="text-right">จำนวน</th>
                                                    <th class="text-right">รายได้</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${sortedServiceTypes.length > 0 ? 
                                                    sortedServiceTypes.slice(0, 5).map(type => `
                                                        <tr>
                                                            <td>${type.name}</td>
                                                            <td class="text-right">${type.count}</td>
                                                            <td class="text-right">${type.revenue.toFixed(2)} บาท</td>
                                                        </tr>
                                                    `).join('') : 
                                                    `<tr><td colspan="3" class="text-center">ไม่มีข้อมูล</td></tr>`
                                                }
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">พนักงานให้บริการสูงสุด</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>พนักงาน</th>
                                                    <th class="text-right">จำนวน</th>
                                                    <th class="text-right">รายได้</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${sortedEmployees.length > 0 ? 
                                                    sortedEmployees.slice(0, 5).map(employee => `
                                                        <tr>
                                                            <td>${employee.name}</td>
                                                            <td class="text-right">${employee.count}</td>
                                                            <td class="text-right">${employee.revenue.toFixed(2)} บาท</td>
                                                        </tr>
                                                    `).join('') : 
                                                    `<tr><td colspan="3" class="text-center">ไม่มีข้อมูล</td></tr>`
                                                }
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">รายการการจองคิว</h5>
                        <small>จำนวน ${filteredBookings.length} รายการ</small>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>บริการ</th>
                                    <th>ลูกค้า</th>
                                    <th>พนักงาน</th>
                                    <th class="text-right">ราคา</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filteredBookings.length > 0 ? 
                                    filteredBookings.map(booking => `
                                        <tr>
                                            <td>${formatDateShort(booking.appointment_datetime)}</td>
                                            <td>${booking.service ? booking.service.name : '-'}</td>
                                            <td>${booking.customer ? booking.customer.firstname + ' ' + booking.customer.lastname : '-'}</td>
                                            <td>${booking.user ? booking.user.firstname + ' ' + booking.user.lastname : 'ยังไม่กำหนด'}</td>
                                            <td class="text-right">${parseFloat(booking.total_price || 0).toFixed(2)} บาท</td>
                                            <td>
                                                ${getStatusBadge(booking.status)}
                                            </td>
                                        </tr>
                                    `).join('') : 
                                    `<tr><td colspan="6" class="text-center">ไม่พบข้อมูลการจองในช่วงเวลาที่เลือก</td></tr>`
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            
            // แสดงรายงาน
            $('#bookingSummary').html(reportHtml);
        }
        
        
        // ฟอร์แมตวันที่แบบยาว
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('th-TH', options);
        }
        
        // ฟอร์แมตวันที่แบบสั้น
        function formatDateShort(dateString) {
            const date = new Date(dateString);
            return `${date.getDate().toString().padStart(2, '0')}/${(date.getMonth() + 1).toString().padStart(2, '0')}/${date.getFullYear()} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}`;
        }
        
        // ฟอร์แมตวันที่สำหรับ input
        function formatDateForInput(date) {
            return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')}`;
        }
        
        // รับข้อความสถานะ
        function getStatusText(status) {
            switch(status) {
                case 'pending': return 'รอยืนยัน';
                case 'confirm': return 'ยืนยันแล้ว';
                case 'cancel': return 'ยกเลิก';
                case 'complete': return 'เสร็จสิ้น';
                default: return status;
            }
        }
        
        // รับ badge สถานะ
        function getStatusBadge(status) {
            let badgeClass = '';
            let statusText = '';
            
            switch(status) {
                case 'pending':
                    badgeClass = 'badge-warning';
                    statusText = 'รอยืนยัน';
                    break;
                case 'confirm':
                    badgeClass = 'badge-primary';
                    statusText = 'ยืนยันแล้ว';
                    break;
                case 'cancel':
                    badgeClass = 'badge-danger';
                    statusText = 'ยกเลิก';
                    break;
                case 'complete':
                    badgeClass = 'badge-success';
                    statusText = 'เสร็จสิ้น';
                    break;
                default:
                    badgeClass = 'badge-secondary';
                    statusText = status;
            }
            
            return `<span class="badge ${badgeClass}">${statusText}</span>`;
        }
    });
</script>