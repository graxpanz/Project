<div class="card shadow">
    <div class="card-header border-0 pt-4">
        <h4>
            <i class="fas fa-calendar-alt"></i>
            ปฏิทินการปฏิบัติงาน
        </h4>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal สำหรับแสดงรายละเอียดเมื่อกดที่อีเวนต์ -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">รายละเอียดการนัดหมาย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img id="event-service-image" src="" alt="Service Image" class="img-thumbnail" style="max-height: 120px; width: auto;">
                    </div>
                    <div class="col-md-8">
                        <h5 id="event-service-name" class="font-weight-bold text-primary"></h5>
                        <p class="mb-1">
                            <i class="far fa-clock"></i> <span id="event-time"></span> 
                            (<span id="event-duration"></span> นาที)
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-user"></i> ลูกค้า: <span id="event-customer"></span>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-phone"></i> โทร: <span id="event-phone"></span>
                        </p>
                        <p class="mb-1">
                            <i class="fas fa-user-tie"></i> พนักงาน: <span id="event-staff"></span>
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="font-weight-bold">หมายเหตุ:</h6>
                                <p id="event-note" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="mb-1">
                            <strong>ราคา:</strong> <span id="event-price"></span> บาท
                        </p>
                        <p class="mb-1">
                            <strong>ส่วนลด:</strong> <span id="event-discount"></span> บาท
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1">
                            <strong>มัดจำ:</strong> <span id="event-deposit"></span> บาท
                        </p>
                        <p class="mb-1">
                            <strong>ราคารวม:</strong> <span id="event-total" class="font-weight-bold"></span> บาท
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="mb-1">
                            <strong>สถานะ:</strong> <span id="event-status" class="badge"></span>
                        </p>
                        <p class="mb-1">
                            <strong>สร้างเมื่อ:</strong> <span id="event-created"></span>
                        </p>
                        <p class="mb-1">
                            <strong>อัพเดทล่าสุด:</strong> <span id="event-updated"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <a id="event-edit-link" href="#" class="btn btn-primary">แก้ไข</a>
            </div>
        </div>
    </div>
</div>

<!-- ต้องเพิ่ม FullCalendar JS ก่อนใช้งาน -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/locales/th.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ข้อมูลการจอง (Booking) ที่ได้รับจาก API หรือ Controller
        const bookings = <?php echo json_encode($bookings); ?>;
        
        // แปลงข้อมูลการจองเป็นรูปแบบที่ FullCalendar ต้องการ
        const events = bookings.map(booking => {
            // คำนวณเวลาสิ้นสุดโดยใช้ระยะเวลาบริการ
            const startTime = new Date(booking.appointment_datetime);
            const endTime = new Date(startTime.getTime() + (booking.service.time * 60 * 1000)); // เพิ่มเวลาตามนาที
            
            // กำหนดสีตามสถานะ
            let backgroundColor = '#3788d8'; // สีฟ้าสำหรับสถานะทั่วไป
            let borderColor = '#3788d8';
            
            switch(booking.status) {
                case 'pending':
                    backgroundColor = '#ffc107'; // สีเหลือง (รอยืนยัน)
                    borderColor = '#ffc107';
                    break;
                case 'confirm':
                    backgroundColor = '#28a745'; // สีเขียว (ยืนยันแล้ว)
                    borderColor = '#28a745';
                    break;
                case 'cancel':
                    backgroundColor = '#dc3545'; // สีแดง (ยกเลิก)
                    borderColor = '#dc3545';
                    break;
                case 'complete':
                    backgroundColor = '#6f42c1'; // สีม่วง (เสร็จสิ้น)
                    borderColor = '#6f42c1';
                    break;
            }
            
            let title = booking.service.name;
            if (booking.user && booking.user.firstname) {
                title += ' - ' + booking.user.firstname;
            }
            
            return {
                id: booking.booking_id,
                title: title,
                start: booking.appointment_datetime,
                end: endTime.toISOString(),
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                extendedProps: {
                    booking: booking
                }
            };
        });
        
        // สร้างปฏิทิน
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            locale: 'th',
            timeZone: 'local',
            events: events,
            height: 'auto',
            allDaySlot: false,
            slotDuration: '00:15:00', // ช่วงเวลาแต่ละช่องเป็น 15 นาที
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            firstDay: 0, // วันอาทิตย์เป็นวันแรกของสัปดาห์
            businessHours: {
                // วันและเวลาทำการ
                daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // 0=อาทิตย์, 1=จันทร์, ..., 6=เสาร์
                startTime: '09:00',
                endTime: '20:00',
            },
            eventClick: function(info) {
                const booking = info.event.extendedProps.booking;
                
                // ข้อมูลหลัก
                document.getElementById('event-service-name').innerText = booking.service.name;
                document.getElementById('event-time').innerText = formatDate(booking.appointment_datetime);
                document.getElementById('event-duration').innerText = booking.service.time;
                
                if (booking.customer) {
                    const customerName = `${booking.customer.firstname} ${booking.customer.lastname}`;
                    document.getElementById('event-customer').innerText = customerName;
                    document.getElementById('event-phone').innerText = booking.customer.phone || '-';
                } else {
                    document.getElementById('event-customer').innerText = '-';
                    document.getElementById('event-phone').innerText = '-';
                }
                
                if (booking.user) {
                    const staffName = `${booking.user.firstname} ${booking.user.lastname}`;
                    document.getElementById('event-staff').innerText = staffName;
                } else {
                    document.getElementById('event-staff').innerText = '-';
                }
                
                // ข้อมูลราคา
                document.getElementById('event-price').innerText = booking.price;
                document.getElementById('event-discount').innerText = booking.discount;
                document.getElementById('event-deposit').innerText = booking.deposit_price;
                document.getElementById('event-total').innerText = booking.total_price;
                
                // ข้อมูลเพิ่มเติม
                document.getElementById('event-note').innerText = booking.note || '-';
                
                // รูปภาพบริการ
                const servicePath = booking.service.image ? `/assets/uploads/service/${booking.service.image}` : '/assets/images/no-image.jpg';
                document.getElementById('event-service-image').src = servicePath;
                
                // สถานะและเวลา
                const statusElement = document.getElementById('event-status');
                statusElement.innerText = getStatusText(booking.status);
                statusElement.className = 'badge ' + getStatusClass(booking.status);
                
                document.getElementById('event-created').innerText = formatDateTime(booking.created_at);
                document.getElementById('event-updated').innerText = formatDateTime(booking.updated_at);
                
                // ลิงก์แก้ไข
                document.getElementById('event-edit-link').href = `/booking/edit/${booking.booking_id}`;
                
                // แสดง Modal
                $('#eventModal').modal('show');
            }
        });
        
        calendar.render();
        
        // ฟังก์ชันสำหรับฟอร์แมตวันที่และเวลา
        function formatDate(dateTimeStr) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateTimeStr).toLocaleDateString('th-TH', options);
        }
        
        function formatDateTime(dateTimeStr) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            return new Date(dateTimeStr).toLocaleDateString('th-TH', options);
        }
        
        // ฟังก์ชันแปลงสถานะเป็นข้อความภาษาไทย
        function getStatusText(status) {
            switch(status) {
                case 'pending': return 'รอยืนยัน';
                case 'confirm': return 'ยืนยันแล้ว';
                case 'cancel': return 'ยกเลิก';
                case 'complete': return 'เสร็จสิ้น';
                default: return status;
            }
        }
        
        // ฟังก์ชันกำหนด CSS class ของ badge ตามสถานะ
        function getStatusClass(status) {
            switch(status) {
                case 'pending': return 'badge-warning';
                case 'confirm': return 'badge-success';
                case 'cancel': return 'badge-danger';
                case 'complete': return 'badge-primary';
                default: return 'badge-secondary';
            }
        }
    });
</script>