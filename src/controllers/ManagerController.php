<?php
class ManagerController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = $this->model('User');
    }

    public function index() {
        $managers = $this->userModel->getAllManagers();
        $data = [
            'title' => 'จัดการผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร',
            'managers' => $managers
        ];
        $this->view('manager/index', $data);
    }

    public function add() {
        // เพิ่มการดึงข้อมูล roles
        $roles = $this->model('Role')->getAllRoles();
        
        $data = [
            'title' => 'เพิ่มผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร',
            'roles' => $roles
        ];
        $this->view('manager/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->insertManager($_POST)) {
                redirect()->with('success', 'เพิ่มผู้ดูแลระบบสำเร็จ')->to('/manager');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มผู้ดูแลระบบ')->back();
            }
        }
    }

    public function edit($id) {
        $manager = $this->userModel->getManagerById($id);
        if (!$manager) {
            redirect()->with('error', 'ไม่พบข้อมูลผู้ดูแลระบบ')->to('/manager');
        }
    
        // เพิ่มการดึงข้อมูล roles
        $roles = $this->model('Role')->getAllRoles();
    
        $data = [
            'title' => 'แก้ไขข้อมูลผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร',
            'manager' => $manager,
            'roles' => $roles
        ];
        $this->view('manager/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->userModel->updateManager($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลผู้ดูแลระบบสำเร็จ')->to('/manager');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->userModel->deleteManager($id)) {
            redirect()->with('success', 'ลบข้อมูลผู้ดูแลระบบสำเร็จ')->to('/manager');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}