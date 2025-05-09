<?php
class EstimateController extends Controller {
    private $estimateModel;
    private $customerModel;

    public function __construct() {
        parent::__construct();
        $this->estimateModel = $this->model('Estimate');
        $this->customerModel = $this->model('Customer');
    }

    public function index() {
        $estimates = $this->estimateModel->getAllEstimates();
        $data = [
            'title' => 'จัดการข้อมูลการประเมิน | Mira ศูนย์ความงามครบวงจร',
            'estimates' => $estimates,
        ];
        $this->view('estimate/index', $data);
    }

    public function add() {
        $customers = $this->customerModel->getAllCustomers();
        $data = [
            'title' => 'เพิ่มข้อมูลการประเมิน | Mira ศูนย์ความงามครบวงจร',
            'customers' => $customers
        ];
        $this->view('estimate/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->estimateModel->insertEstimate($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลการประเมินสำเร็จ')->to('/estimate');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id) {
        $estimate = $this->estimateModel->getEstimateById($id);
        if (!$estimate) {
            redirect()->with('error', 'ไม่พบข้อมูลการประเมิน')->to('/estimate');
        }
        $customers = $this->customerModel->getAllCustomers();
        $data = [
            'title' => 'แก้ไขข้อมูลการประเมิน | Mira ศูนย์ความงามครบวงจร',
            'estimate' => $estimate,
            'customers' => $customers
        ];
        $this->view('estimate/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->estimateModel->updateEstimate($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลการประเมินสำเร็จ')->to('/estimate');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->estimateModel->deleteEstimate($id)) {
            redirect()->with('success', 'ลบข้อมูลการประเมินสำเร็จ')->to('/estimate');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function respond($id) {
        $estimate = $this->estimateModel->getEstimateById($id);
        if (!$estimate) {
            redirect()->with('error', 'ไม่พบข้อมูลการประเมิน')->to('/estimate');
        }
        $data = [
            'title' => 'ตอบกลับการประเมิน | Mira ศูนย์ความงามครบวงจร',
            'estimate' => $estimate
        ];
        $this->view('estimate/respond', $data);
    }

    public function update_response() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->estimateModel->updateEstimateResponse($_POST)) {
                redirect()->with('success', 'อัปเดตการตอบกลับสำเร็จ')->to('/estimate');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตการตอบกลับ')->back();
            }
        }
    }

    // API to get customer's estimates
    public function api_estimate_by_customer() {
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
            
            // Get all estimates for the customer
            $estimates = $this->estimateModel->getEstimatesByCustomerId($sessionData['customer_id'], $status);
            
            // Format image URLs
            foreach ($estimates as $key => $estimate) {
                $estimates[$key]['image'] = $estimate['image'] ? "assets/uploads/estimate/" . $estimate['image'] : null;
            }
            
            $this->json([
                'status' => true,
                'message' => 'Estimates retrieved successfully',
                'data' => $estimates
            ]);
    
        } catch (Exception $e) {
            error_log("API estimates by customer error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve estimates',
                'error' => $e->getMessage()
            ]);
        }
    }

    // API to create a new estimate
    public function api_create_estimate() {
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
            
            // Create data array from POST data
            $data = [
                'customer_id' => $sessionData['customer_id'],
                'description' => $_POST['description'] ?? null
            ];
            
            // Validate required fields
            if (empty($data['description'])) {
                $this->json([
                    'status' => false,
                    'message' => 'Description is required'
                ]);
            }
            
            // Insert estimate
            if ($this->estimateModel->insertEstimate($data)) {
                $this->json([
                    'status' => true,
                    'message' => 'Estimate created successfully'
                ]);
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to create estimate'
                ]);
            }
    
        } catch (Exception $e) {
            error_log("API create estimate error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to create estimate',
                'error' => $e->getMessage()
            ]);
        }
    }

    // API to update an estimate
    public function api_update_estimate() {
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
            
            // Check if estimate exists and belongs to the customer
            $estimate_id = $_POST['estimate_id'] ?? null;
            if (!$estimate_id) {
                $this->json([
                    'status' => false,
                    'message' => 'Estimate ID is required'
                ]);
            }
            
            $estimate = $this->estimateModel->getEstimateById($estimate_id);
            if (!$estimate || $estimate['customer_id'] != $sessionData['customer_id']) {
                $this->json([
                    'status' => false,
                    'message' => 'Estimate not found or not authorized'
                ]);
            }
            
            // Create data array
            $data = [
                'estimate_id' => $estimate_id,
                'customer_id' => $sessionData['customer_id'],
                'description' => $_POST['description'] ?? $estimate['description'],
                'response' => $estimate['response'],
                'status' => 'pending', // Reset to pending when customer updates
                'is_active' => $estimate['is_active']
            ];
            
            // Update estimate
            if ($this->estimateModel->updateEstimate($data)) {
                $this->json([
                    'status' => true,
                    'message' => 'Estimate updated successfully'
                ]);
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to update estimate'
                ]);
            }
    
        } catch (Exception $e) {
            error_log("API update estimate error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to update estimate',
                'error' => $e->getMessage()
            ]);
        }
    }

    // API to delete an estimate
    public function api_delete_estimate() {
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
            
            // Check if estimate exists and belongs to the customer
            $estimate_id = $_POST['estimate_id'] ?? null;
            if (!$estimate_id) {
                $data = json_decode(file_get_contents('php://input'), true);
                if (!$data['estimate_id']) {
                    $this->json([
                        'status' => false,
                        'message' => 'Estimate ID is required'
                    ]);
                } else {
                    $estimate_id = $data['estimate_id'];
                }
            }
            
            
            $estimate = $this->estimateModel->getEstimateById($estimate_id);
            if (!$estimate || $estimate['customer_id'] != $sessionData['customer_id']) {
                $this->json([
                    'status' => false,
                    'message' => 'Estimate not found or not authorized'
                ]);
            }
            
            // Delete the estimate
            if ($this->estimateModel->deleteEstimate($estimate_id)) {
                $this->json([
                    'status' => true,
                    'message' => 'Estimate deleted successfully'
                ]);
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to delete estimate'
                ]);
            }
    
        } catch (Exception $e) {
            error_log("API delete estimate error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to delete estimate',
                'error' => $e->getMessage()
            ]);
        }
    }
    
}