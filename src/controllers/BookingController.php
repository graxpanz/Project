<?php
class BookingController extends Controller {
    private $bookingModel;
    private $customerModel;
    private $serviceModel;
    private $userModel;
    private $promotionModel;
    private $transactionModel;

    public function __construct() {
        parent::__construct();
        $this->bookingModel = $this->model('Booking');
        $this->customerModel = $this->model('Customer');
        $this->serviceModel = $this->model('Service');
        $this->userModel = $this->model('User');
        $this->promotionModel = $this->model('Promotion');
        $this->transactionModel = $this->model('Transaction');
    }

    public function index() {
        $bookings = $this->bookingModel->getAllBookings();
        // return $this->json($bookings);
        $data = [
            'title' => 'จัดการข้อมูลการจอง | Mira ศูนย์ความงามครบวงจร',
            'bookings' => $bookings,
        ];
        $this->view('booking/index', $data);
    }

    public function add() {
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

    public function insert() {
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
                redirect()->with('success', 'เพิ่มข้อมูลการจองสำเร็จ')->to('/booking');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        } else {
            redirect()->to('/booking');
        }
    }

    public function edit($id) {
        $booking = $this->bookingModel->getBookingById($id);
        if (!$booking) {
            redirect()->with('error', 'ไม่พบข้อมูลการจอง')->to('/booking');
        }
        
        $customers = $this->customerModel->getAllCustomers();
        $services = $this->serviceModel->getAllServices();
        $employees = $this->userModel->getAllEmployees();
        $promotions = $this->promotionModel->getAllPromotions();
        $transactions = $this->transactionModel->getTransactionsByBookingId($booking['booking_id']);
        
        $data = [
            'title' => 'แก้ไขข้อมูลการจอง | Mira ศูนย์ความงามครบวงจร',
            'booking' => $booking,
            'customers' => $customers,
            'services' => $services,
            'employees' => $employees,
            'promotions' => $promotions,
            'transactions' => $transactions
        ];
        $this->view('booking/edit', $data);
    }

    public function update() {
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
            
            if ($this->bookingModel->updateBooking($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลการจองสำเร็จ')->to('/booking');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        } else {
            redirect()->to('/booking');
        }
    }

    public function delete($id) {
        if ($this->bookingModel->deleteBooking($id)) {
            redirect()->with('success', 'ลบข้อมูลการจองสำเร็จ')->to('/booking');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function api_booking_add() {
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
            
            // Get the complete booking data to return
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

    public function api_booking_by_customer() {
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
    
            // Get optional status parameter
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            
            // Get all bookings for the customer
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

    public function api_booking_cancel() {
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
            // Get data from request body
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validate required fields
            // $required = ['booking_id', 'status'];
            $required = ['booking_id'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->json([
                        'status' => false,
                        'message' => "Missing required field: {$field}"
                    ]);
                }
            }
            
            // Validate booking exists
            $booking = $this->bookingModel->getBookingById($data['booking_id']);
            if (!$booking) {
                $this->json([
                    'status' => false,
                    'message' => 'Booking not found'
                ]);
            }
            
            // Validate status value
            // $validStatuses = ['cancel'];
            // if (!in_array($data['status'], $validStatuses)) {
            //     $this->json([
            //         'status' => false,
            //         'message' => 'Invalid status value. Valid values are: ' . implode(', ', $validStatuses)
            //     ]);
            // }
            
            // Update booking status
            $result = $this->bookingModel->updateBookingStatus($data['booking_id'], 'cancel');
            
            if (!$result) {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to update booking status'
                ]);
            }
            
            // Get updated booking
            $updatedBooking = $this->bookingModel->getBookingById($data['booking_id']);
            
            $this->json([
                'status' => true,
                'message' => 'Booking status updated successfully',
                'data' => $updatedBooking
            ]);
    
        } catch (Exception $e) {
            error_log("API update booking status error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to update booking status',
                'error' => $e->getMessage()
            ]);
        }
    }
}