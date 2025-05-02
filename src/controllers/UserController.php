<?php
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        $data = [
            'title' => 'จัดการผู้ใช้ | Mira ศูนย์ความงามครบวงจร',
            'users' => $users
        ];
        $this->view('user/index', $data);
    }

    public function add() {
        $roles = $this->model('Role')->getAllRoles();
        $data = [
            'title' => 'เพิ่มผู้ใช้ | Mira ศูนย์ความงามครบวงจร',
            'roles' => $roles
        ];
        $this->view('user/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->insertUser($_POST)) {
                redirect()->with('success', 'เพิ่มผู้ใช้สำเร็จ')->to('/user');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้')->back();
            }
        }
    }

    public function edit($id) {
        $user = $this->userModel->getUserById($id);
        if (!$user) {
            redirect()->with('error', 'ไม่พบข้อมูลผู้ใช้')->to('/user');
        }
    
        // เพิ่มการดึงข้อมูล roles
        $roles = $this->model('Role')->getAllRoles();
    
        $data = [
            'title' => 'แก้ไขข้อมูลผู้ใช้ | Mira ศูนย์ความงามครบวงจร',
            'user' => $user,
            'roles' => $roles
        ];
        $this->view('user/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->updateUser($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลผู้ใช้สำเร็จ')->to('/user');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->userModel->deleteUser($id)) {
            redirect()->with('success', 'ลบข้อมูลผู้ใช้สำเร็จ')->to('/user');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function api_employee() {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        try {
            $employees = $this->userModel->getAllEmployees();
            foreach ($employees as $key => $employee) {
                $employee[$key]['image'] = $employee['image'] ? "assets/uploads/user/" . $employee['image'] : null;
            }
            $this->json([
                'status' => true,
                'message' => 'Employees data retrieved successfully',
                'data' => $employees
            ]);

        } catch (Exception $e) {
            error_log("API service error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve employees data',
                'error' => $e->getMessage()
            ]);
        }
    }


}