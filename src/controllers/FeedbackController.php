<?php
class FeedbackController extends Controller {
    private $feedbackModel;
    private $customerModel;
    private $serviceModel;

    public function __construct() {
        parent::__construct();
        $this->feedbackModel = $this->model('Feedback');
        $this->customerModel = $this->model('Customer');
        $this->serviceModel = $this->model('Service');
    }

    public function index() {
        $feedback = $this->feedbackModel->getAllFeedback();
        $data = [
            'title' => 'จัดการข้อมูลความคิดเห็น | Mira ศูนย์ความงามครบวงจร',
            'feedback' => $feedback
        ];
        $this->view('feedback/index', $data);
    }

    public function add() {
        $customers = $this->customerModel->getAllCustomers();
        $services = $this->serviceModel->getAllServices();
        $data = [
            'title' => 'เพิ่มความคิดเห็น | Mira ศูนย์ความงามครบวงจร',
            'customers' => $customers,
            'services' => $services
        ];
        $this->view('feedback/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->feedbackModel->insertFeedback($_POST)) {
                redirect()->with('success', 'เพิ่มความคิดเห็นสำเร็จ')->to('/feedback');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มความคิดเห็น')->back();
            }
        }
    }

    public function edit($id) {
        $feedback = $this->feedbackModel->getFeedbackById($id);
        if (!$feedback) {
            redirect()->with('error', 'ไม่พบข้อมูลความคิดเห็น')->to('/feedback');
        }
        
        $customers = $this->model('Customer')->getAllCustomers();
        $services = $this->model('Service')->getAllServices();
        
        $data = [
            'title' => 'แก้ไขข้อมูลความคิดเห็น | Mira ศูนย์ความงามครบวงจร',
            'feedback' => $feedback,
            'customers' => $customers,
            'services' => $services
        ];
        $this->view('feedback/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->feedbackModel->updateFeedback($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลความคิดเห็นสำเร็จ')->to('/feedback');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->feedbackModel->deleteFeedback($id)) {
            redirect()->with('success', 'ลบข้อมูลความคิดเห็นสำเร็จ')->to('/feedback');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function api_feedback() {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        
        try {
            $feedback = $this->feedbackModel->getAllFeedback();
            $this->json([
                'status' => true,
                'message' => 'Feedback data retrieved successfully',
                'data' => $feedback
            ]);
        } catch (Exception $e) {
            error_log("API feedback error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve feedback data',
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_service_feedback($serviceId) {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        
        try {
            $feedback = $this->feedbackModel->getFeedbackByServiceId($serviceId);
            $this->json([
                'status' => true,
                'message' => 'Service feedback data retrieved successfully',
                'data' => $feedback
            ]);
        } catch (Exception $e) {
            error_log("API service feedback error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve service feedback data',
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function api_feedback_add() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
            return;
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

            $data = json_decode(file_get_contents('php://input'), true);
            $data['customer_id'] = $sessionData['customer_id'];
            
            // ตรวจสอบข้อมูลที่ส่งมา
            $requiredFields = ['rating', 'comment'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $this->json([
                    'status' => false,
                    'message' => 'Missing required fields',
                    'fields' => $missingFields
                ]);
                return;
            }
        
            if ($this->feedbackModel->insertFeedback($data)) {
                $this->json([
                    'status' => true,
                    'message' => 'Feedback submitted successfully'
                ]);
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to submit feedback'
                ]);
            }
        } catch (Exception $e) {
            error_log("API submit feedback error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'An error occurred while submitting feedback',
                'error' => $e->getMessage()
            ]);
        }
    }
}