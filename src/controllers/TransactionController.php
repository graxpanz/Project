<?php
class TransactionController extends Controller
{
    private $transactionModel;
    private $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = $this->model('Transaction');
        $this->customerModel = $this->model('Customer');
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'booking_id' => $_POST['booking_id'] ?? null
            ];

            // Validate required fields
            if (empty($data['booking_id'])) {
                redirect()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน')->back();
            }

            // Validate image upload
            if (!isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                redirect()->with('error', 'กรุณากรอกข้อมูลให้ครบถ้วน')->back();
            }

            // Insert transaction
            if ($this->transactionModel->insertTransaction($data)) {
                redirect()->with('success', 'เพิ่มข้อมูลหลักฐานการโอนเงินสำเร็จแล้ว')->back();
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        } else {
            redirect()->back();
        }
    }

    public function api_add_transaction()
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

            // Create data array from POST data
            $data = [
                'booking_id' => $_POST['booking_id'] ?? null
            ];

            // Validate required fields
            if (empty($data['booking_id'])) {
                $this->json([
                    'status' => false,
                    'message' => 'Booking ID is required'
                ]);
            }

            // Validate image upload
            if (!isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->json([
                    'status' => false,
                    'message' => 'Image is required'
                ]);
            }

            // Insert transaction
            if ($this->transactionModel->insertTransaction($data)) {
                $this->json([
                    'status' => true,
                    'message' => 'Transaction added successfully'
                ]);
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Failed to add transaction'
                ]);
            }
        } catch (Exception $e) {
            error_log("API create transaction error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ]);
        }
    }
}
