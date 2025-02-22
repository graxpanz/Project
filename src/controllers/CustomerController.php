<?php
class CustomerController extends Controller {
    private $customerModel;

    public function __construct() {
        parent::__construct();
        $this->customerModel = $this->model('Customer');
    }

    public function index() {
        $customers = $this->customerModel->getAllCustomer();
        $data = [
            'title' => 'จัดการข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร',
            'customers' => $customers
        ];
        $this->view('customer/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร',
        ];
        $this->view('customer/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect()->with('error', 'Invalid request method')->to('/customer');
        }

        // Validate required fields
        $required = ['firstname', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                redirect()->with('error', "กรุณากรอก{$field}")->back();
                return;
            }
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            redirect()->with('error', 'รูปแบบอีเมลไม่ถูกต้อง')->back();
            return;
        }

        // Hash password
        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Set default values
        $_POST['is_active'] = '1';
        
        try {
            $customerId = $this->customerModel->insertCustomer($_POST);
            if ($customerId) {
                redirect()->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลลูกค้า')->back();
            }
        } catch (Exception $e) {
            // Log error
            error_log("Error inserting customer: " . $e->getMessage());
            redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลลูกค้า')->back();
        }
    }

    public function edit($id) {
        if (!$id || !is_numeric($id)) {
            redirect()->with('error', 'Invalid customer ID')->to('/customer');
        }

        $customer = $this->customerModel->getCustomerById($id);
        if (!$customer) {
            redirect()->with('error', 'ไม่พบข้อมูลลูกค้า')->to('/customer');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร',
            'customer' => $customer
        ];
        $this->view('customer/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect()->with('error', 'Invalid request method')->to('/customer');
        }

        // Validate customer_id
        if (empty($_POST['customer_id']) || !is_numeric($_POST['customer_id'])) {
            redirect()->with('error', 'Invalid customer ID')->to('/customer');
        }

        // Validate required fields
        $required = ['firstname', 'email'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                redirect()->with('error', "กรุณากรอก{$field}")->back();
                return;
            }
        }

        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            redirect()->with('error', 'รูปแบบอีเมลไม่ถูกต้อง')->back();
            return;
        }

        // Validate birthdate
        $_POST['birthdate'] = $_POST['birthdate'] ? $_POST['birthdate'] : NULL;

        // Handle is_active checkbox
        $_POST['is_active'] = isset($_POST['is_active']) ? '1' : '0';

        try {
            $result = $this->customerModel->updateCustomer($_POST);
            if ($result) {
                redirect()->with('success', 'อัปเดตข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูลลูกค้า')->back();
            }
        } catch (Exception $e) {
            // Log error
            print_r( $e->getMessage());
            die();
            error_log("Error updating customer: " . $e->getMessage());
            redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูลลูกค้า')->back();
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect()->with('error', 'Invalid request method')->to('/customer');
        }

        if (!$id || !is_numeric($id)) {
            redirect()->with('error', 'Invalid customer ID')->to('/customer');
        }

        try {
            $result = $this->customerModel->deleteCustomer($id);
            if ($result) {
                redirect()->with('success', 'ลบข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูลลูกค้า')->to('/customer');
            }
        } catch (Exception $e) {
            // Log error
            error_log("Error deleting customer: " . $e->getMessage());
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูลลูกค้า')->to('/customer');
        }
    }

    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method']);
        }

        if (!$id || !is_numeric($id)) {
            $this->json(['success' => false, 'message' => 'Invalid customer ID']);
        }

        try {
            $status = isset($_POST['status']) ? $_POST['status'] : '0';
            $result = $this->customerModel->updateStatus($id, $status);
            
            $this->json([
                'success' => $result,
                'message' => $result ? 'Updated successfully' : 'Update failed'
            ]);
        } catch (Exception $e) {
            error_log("Error toggling customer status: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Internal server error']);
        }
    }

    public function api_register() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }

        try {
            // Validate input
            $required = ['firstname', 'email', 'password'];
            $data = json_decode(file_get_contents('php://input'), true);
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->json([
                        'status' => false,
                        'message' => "Missing required field: {$field}"
                    ]);
                }
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->json([
                    'status' => false,
                    'message' => 'Invalid email format'
                ]);
            }

            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Set default values
            $data['is_active'] = '1';

            $customerId = $this->customerModel->insertCustomer($data);
            
            $this->json([
                'status' => true,
                'message' => 'Registration successful',
                'data' => ['customer_id' => $customerId]
            ]);

        } catch (Exception $e) {
            error_log("API registration error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ]);
        }
    }
}