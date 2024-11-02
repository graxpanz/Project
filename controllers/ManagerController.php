<?php
class ManagerController extends Controller {
    private $managerModel;

    public function __construct() {
        // ตรวจสอบสิทธิ์การเข้าถึง
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login');
        }
        $this->managerModel = $this->model('Manager');
    }

    public function index() {
        $managers = $this->managerModel->getAllManagers();
        $data = [
            'title' => 'จัดการผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร',
            'managers' => $managers
        ];
        $this->view('manager/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร'
        ];
        $this->view('manager/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->managerModel->insertManager($_POST)) {
                redirect()->with('success', 'เพิ่มผู้ดูแลระบบสำเร็จ')->to('/manager');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มผู้ดูแลระบบ')->back();
            }
        }
    }

    public function edit($id) {
        $manager = $this->managerModel->getManagerById($id);
        if (!$manager) {
            redirect()->with('error', 'ไม่พบข้อมูลผู้ดูแลระบบ')->to('/manager');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลผู้ดูแลระบบ | Mira ศูนย์ความงามครบวงจร',
            'manager' => $manager
        ];
        $this->view('manager/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->managerModel->updateManager($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลผู้ดูแลระบบสำเร็จ')->to('/manager');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->managerModel->deleteManager($id)) {
            redirect()->with('success', 'ลบข้อมูลผู้ดูแลระบบสำเร็จ')->to('/manager');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}