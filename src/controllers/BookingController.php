<?php
class BookingController extends Controller
{
    private $bookingModel;
    private $customerModel;
    private $serviceModel;
    private $userModel;
    private $promotionModel;

    public function __construct()
    {
        parent::__construct();
        $this->bookingModel = $this->model('Booking');
        $this->customerModel = $this->model('Customer');
        $this->serviceModel = $this->model('Service');
        $this->userModel = $this->model('User');
        $this->promotionModel = $this->model('Promotion');
    }

    public function index()
    {
        $bookings = $this->bookingModel->getAllBookings();
        $data = [
            'title' => 'จัดการข้อมูลการจอง | Mira ศูนย์ความงามครบวงจร',
            'bookings' => $bookings,
        ];
        $this->view('booking/index', $data);
    }

    public function add()
    {
        $customers = $this->customerModel->getAllCustomers();
        $services = $this->serviceModel->getAllServices();
        $employees = $this->userModel->getAllEmployees();
        $promotions = $this->promotionModel->getAllPromotions();
        $data = [
            'title' => 'เพิ่มข้อมูลการจอง | Mira ศูนย์ความงามครบวงจร',
            'customers' => $customers,
            'services' => $services,
            'employees' => $employees,
            'promotions' => $promotions,
        ];
        $this->view('booking/add', $data);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate required fields
            $requiredFields = ['customer_id', 'service_id', 'appointment_date', 'appointment_time', 'price', 'total_price'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    redirect()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน')->back();
                    exit;
                }
            }

            // Ensure price values are numeric
            $numericFields = ['price', 'discount', 'deposit_price', 'total_price'];
            foreach ($numericFields as $field) {
                if (isset($_POST[$field])) {
                    $_POST[$field] = (float) $_POST[$field];
                }
            }

            if ($this->bookingModel->insertBooking($_POST)) {
                // ส่งการแจ้งเตือนไปยัง LINE เมื่อมีการจองคิวสำเร็จ
                $customer = $this->customerModel->getCustomerById($_POST['customer_id']);
                $service = $this->serviceModel->getServiceById($_POST['service_id']);

                if ($customer && $service) {
                    $messageText = "📌 มีการจองคิวใหม่\n";
                    $messageText .= "ลูกค้า: " . $customer['firstname'] . " " . $customer['lastname'] . "\n";
                    $messageText .= "บริการ: " . $service['name'] . "\n";
                    $messageText .= "วันที่: " . $_POST['appointment_date'] . "\n";
                    $messageText .= "เวลา: " . $_POST['appointment_time'] . " น.\n";
                    $messageText .= "ราคา: " . number_format($_POST['total_price'], 2) . " บาท";

                    $this->sendLineMessage('Ce80c1ba5e030cf2808fddbfffe88501d', $messageText);
                }

                redirect()->with('success', 'เพิ่มข้อมูลการจองสำเร็จ')->to('/booking');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        } else {
            redirect()->to('/booking');
        }
    }

    public function edit($id)
    {
        $booking = $this->bookingModel->getBookingById($id);
        if (!$booking) {
            redirect()->with('error', 'ไม่พบข้อมูลการจอง')->to('/booking');
        }

        $customers = $this->customerModel->getAllCustomers();
        $services = $this->serviceModel->getAllServices();
        $employees = $this->userModel->getAllEmployees();
        $promotions = $this->promotionModel->getAllPromotions();

        $data = [
            'title' => 'แก้ไขข้อมูลการจอง | Mira ศูนย์ความงามครบวงจร',
            'booking' => $booking,
            'customers' => $customers,
            'services' => $services,
            'employees' => $employees,
            'promotions' => $promotions
        ];
        $this->view('booking/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate required fields
            $requiredFields = ['booking_id', 'customer_id', 'service_id', 'appointment_date', 'appointment_time', 'price', 'total_price', 'status'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    redirect()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน')->back();
                    exit;
                }
            }

            $numericFields = ['price', 'discount', 'deposit_price', 'total_price'];
            foreach ($numericFields as $field) {
                if (isset($_POST[$field])) {
                    $_POST[$field] = (float) $_POST[$field];
                }
            }

            // ตรวจสอบการเปลี่ยนสถานะการจอง
            $currentBooking = $this->bookingModel->getBookingById($_POST['booking_id']);

            if ($this->bookingModel->updateBooking($_POST)) {
                // ถ้ามีการเปลี่ยนสถานะ ให้ส่งการแจ้งเตือนไปยัง LINE
                if ($currentBooking && $currentBooking['status'] !== $_POST['status']) {
                    $customer = $this->customerModel->getCustomerById($_POST['customer_id']);
                    $service = $this->serviceModel->getServiceById($_POST['service_id']);

                    if ($customer && $service) {
                        $statusThai = $this->getThaiStatus($_POST['status']);

                        $messageText = "📝 เปลี่ยนสถานะการจอง\n";
                        $messageText .= "ลูกค้า: " . $customer['firstname'] . " " . $customer['lastname'] . "\n";
                        $messageText .= "บริการ: " . $service['name'] . "\n";
                        $messageText .= "วันที่เข้ารับบริการ: " . $_POST['appointment_date'] . "\n";
                        $messageText .= "เวลา: " . $_POST['appointment_time'] . " น.\n";
                        $messageText .= "สถานะ: " . $statusThai;

                        $this->sendLineMessage('Ce80c1ba5e030cf2808fddbfffe88501d', $messageText);
                    }
                }

                redirect()->with('success', 'อัปเดตข้อมูลการจองสำเร็จ')->to('/booking');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        } else {
            redirect()->to('/booking');
        }
    }

    public function delete($id)
    {
        $booking = $this->bookingModel->getBookingById($id);

        if ($this->bookingModel->deleteBooking($id)) {
            // ส่งการแจ้งเตือนไปยัง LINE เมื่อมีการลบข้อมูลการจอง
            if ($booking) {
                $customer = $this->customerModel->getCustomerById($booking['customer_id']);
                $service = $this->serviceModel->getServiceById($booking['service_id']);

                if ($customer && $service) {
                    $messageText = "❌ มีการลบข้อมูลการจอง\n";
                    $messageText .= "ลูกค้า: " . $customer['firstname'] . " " . $customer['lastname'] . "\n";
                    $messageText .= "บริการ: " . $service['name'] . "\n";
                    $messageText .= "วันที่เข้ารับบริการ: " . $booking['appointment_date'] . "\n";
                    $messageText .= "เวลา: " . $booking['appointment_time'] . " น.";

                    $this->sendLineMessage('Ce80c1ba5e030cf2808fddbfffe88501d', $messageText);
                }
            }

            redirect()->with('success', 'ลบข้อมูลการจองสำเร็จ')->to('/booking');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    // แปลงรหัสสถานะเป็นภาษาไทย
    private function getThaiStatus($status)
    {
        $statusMap = [
            'pending' => 'รอดำเนินการ',
            'confirm' => 'ยืนยันแล้ว',
            'complete' => 'เสร็จสิ้น',
            'cancel' => 'ยกเลิก'
        ];

        return isset($statusMap[$status]) ? $statusMap[$status] : $status;
    }

    // LINE Message
    private function sendLineMessage($groupId, $messageText)
    {
        $accessToken = 'nd3PJ+24pHHsvg5T7pClMgTnE00StV0gfYpvLKw87bFtWIvfgBpKHLAtBFumrartJZaw0YkLNUZJvyZVoI/bSaPhM5d6DYtQ/NPZriWW7a/Cn3zRILXMUdEazEDpLsNiVJ9zPqB1ID8HjsSy/o7HkwdB04t89/1O/w1cDnyilFU=';
        $url = 'https://api.line.me/v2/bot/message/push';

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ];

        $body = json_encode([
            'to' => $groupId,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $messageText
                ]
            ]
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    public function api_booking_add()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $token = $this->getAuthToken();
            if (!$token) {
                $this->json([
                    'status' => false,
                    'message' => 'No authentication token provided'
                ]);
            }

            $sessionData = $this->customerModel->validateSession($token);
            if (!$sessionData) {
                $this->json([
                    'status' => false,
                    'message' => 'Invalid or expired token'
                ]);
            }

            // Validate input
            $required = ['customer_id', 'service_id', 'appointment_date', 'appointment_time'];
            $data = json_decode(file_get_contents('php://input'), true);

            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->json([
                        'status' => false,
                        'message' => "Missing required field: {$field}"
                    ]);
                }
            }

            // Validate customer exists
            $customer = $this->customerModel->getCustomerById($data['customer_id']);
            if (!$customer) {
                $this->json([
                    'status' => false,
                    'message' => 'Customer not found'
                ]);
            }

            // Validate service exists
            $service = $this->serviceModel->getServiceById($data['service_id']);
            if (!$service) {
                $this->json([
                    'status' => false,
                    'message' => 'Service not found'
                ]);
            }

            // Validate appointment date and time
            $appointmentDatetime = $data['appointment_date'] . ' ' . $data['appointment_time'] . ':00';
            if (strtotime($appointmentDatetime) < time()) {
                $this->json([
                    'status' => false,
                    'message' => 'Appointment cannot be in the past'
                ]);
            }

            // Set default values and process data
            $bookingData = [
                'customer_id' => $data['customer_id'],
                'service_id' => $data['service_id'],
                'user_id' => $data['user_id'] ?? null,
                'promotion_id' => $data['promotion_id'] ?? null,
                'appointment_date' => $data['appointment_date'],
                'appointment_time' => $data['appointment_time'],
                'price' => $service['price'],
                'discount' => 0,
                'total_price' => $service['price'],
                'status' => 'pending',
                'note' => $data['note'] ?? null
            ];

            // If promotion is provided, apply discount
            if (!empty($data['promotion_id'])) {
                $promotion = $this->promotionModel->getPromotionById($data['promotion_id']);
                if ($promotion) {
                    $bookingData['discount'] = $promotion['discount'];
                    $bookingData['total_price'] = $service['price'] - $promotion['discount'];
                    if ($bookingData['total_price'] < 0) {
                        $bookingData['total_price'] = 0;
                    }
                }
            }

            // Calculate deposit (10% of total)
            $bookingData['deposit_price'] = $bookingData['total_price'] * 0.1;

            // Insert booking
            $bookingId = $this->bookingModel->insertBooking($bookingData);

            if (!$bookingId) {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to create booking'
                ]);
            }

            // ส่งการแจ้งเตือนไปยัง LINE เมื่อมีการจองคิวสำเร็จผ่าน API
            $messageText = "📱 มีการจองคิวใหม่\n";
            $messageText .= "ลูกค้า: " . $customer['firstname'] . " " . $customer['lastname'] . "\n";
            $messageText .= "บริการ: " . $service['name'] . "\n";
            $messageText .= "เข้ารับบริการวันที่: " . $data['appointment_date'] . "\n";
            $messageText .= "เวลา: " . $data['appointment_time'] . " น.\n";
            $messageText .= "ราคา: " . number_format($bookingData['total_price'], 2) . " บาท" ."\n";
            $messageText .= "มัดจำแล้ว: " . number_format($bookingData['deposit_price'], 2) . " บาท";

            $this->sendLineMessage('Ce80c1ba5e030cf2808fddbfffe88501d', $messageText);

            $newBooking = $this->bookingModel->getBookingById($bookingId);

            $this->json([
                'status' => true,
                'message' => 'Booking created successfully',
                'data' => $newBooking
            ]);

        } catch (Exception $e) {
            error_log("API booking add error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Booking creation failed',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_booking_by_customer()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            $token = $this->getAuthToken();
            if (!$token) {
                $this->json([
                    'status' => false,
                    'message' => 'No authentication token provided'
                ]);
            }

            $sessionData = $this->customerModel->validateSession($token);
            if (!$sessionData) {
                $this->json([
                    'status' => false,
                    'message' => 'Invalid or expired token'
                ]);
            }

            $status = isset($_GET['status']) ? $_GET['status'] : null;

            $bookings = $this->bookingModel->getBookingsByCustomerId($sessionData['customer_id'], $status);

            $this->json([
                'status' => true,
                'message' => 'Bookings retrieved successfully',
                'data' => $bookings
            ]);


        } catch (Exception $e) {
            error_log("API bookings by customer error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve bookings',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_booking_cancel()
    {
        ini_set('display_errors', 0);
        error_reporting(0);

        header('Content-Type: application/json; charset=utf-8');

        if (ob_get_level()) {
            ob_end_clean();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => false, 'message' => 'Invalid JSON: ' . json_last_error_msg()]);
            exit;
        }

        $token = $this->getAuthToken();
        if (!$token) {
            echo json_encode(['status' => false, 'message' => 'No authentication token provided']);
            exit;
        }
        $session = $this->customerModel->validateSession($token);
        if (!$session) {
            echo json_encode(['status' => false, 'message' => 'Invalid or expired token']);
            exit;
        }

        if (empty($data['booking_id'])) {
            echo json_encode(['status' => false, 'message' => 'Missing booking_id']);
            exit;
        }
        $booking = $this->bookingModel->getBookingById($data['booking_id']);
        if (!$booking) {
            echo json_encode(['status' => false, 'message' => 'Booking not found']);
            exit;
        }

        if (!$this->bookingModel->updateBookingStatus($data['booking_id'], 'cancel')) {
            echo json_encode(['status' => false, 'message' => 'Failed to update booking status']);
            exit;
        }

        $cust = $this->customerModel->getCustomerById($booking['customer_id']);
        $srv = $this->serviceModel->getServiceById($booking['service_id']);
        if ($cust && $srv) {
            $txt = "❌ ลูกค้ายกเลิกการจองคิว\n";
            $txt .= "ลูกค้า: {$cust['firstname']} {$cust['lastname']}\n";
            $txt .= "บริการ: {$srv['name']}\n";
            $txt .= "เข้ารับบริการวันที่: " . $booking['appointment_date'] . "\n";
            $txt .= "เวลา: " . $booking['appointment_time'] . " น.\n";
            $this->sendLineMessage('Ce80c1ba5e030cf2808fddbfffe88501d', $txt);
        }

        $updated = $this->bookingModel->getBookingById($data['booking_id']);
        echo json_encode([
            'status' => true,
            'message' => 'Booking status updated successfully',
            'data' => $updated
        ]);
        exit;
    }




}