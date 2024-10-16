<?php
class CustomerController extends Controller {
    private $customerModel;

    public function __construct() {
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login'); 
        }

        $this->customerModel = $this->model('Customer');
    }

    public function index() {
        $customer = $this->customerModel->getAllCustomer();
        $data = [
            'title' => 'จัดการข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร',
            'customers' => $customer
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customerId = $this->customerModel->insertCustomer($_POST);
            if ($customerId) {               
                redirect()->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลลูกค้า')->back();
            }
        }
    }

    public function edit($id) {
        $customer = $this->customerModel->getCustomerById($id);
        if (!$customer) {
            redirect()->with('error', 'ไม่พบข้อมูลลูกค้า')->back();
        }
        $data = [
            'title' => 'แก้ไขข้อมูลลูกค้า | Mira ศูนย์ความงามครบวงจร',
            'customer' => $customer           
        ];
        $this->view('customer/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->customerModel->updateCustomer($_POST);
            if ($result) {
                redirect()->with('success', 'อัปเดตข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูลลูกค้า')->back();
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->customerModel->deleteCustomer($id);
            if ($result) {
                redirect()->with('success', 'ลบข้อมูลลูกค้าสำเร็จ')->to('/customer');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูลลูกค้า')->back();
            }
        }
    }
}