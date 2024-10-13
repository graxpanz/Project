<?php
class EmployeeController extends Controller {
    private $employeeModel;

    public function __construct() {
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login'); 
        }

        $this->employeeModel = $this->model('Employee');
    }

    public function index() {
        $employees = $this->employeeModel->getAllEmployees();
        $data = [
            'title' => 'จัดการข้อมูลพนักงาน | Mira ศูนย์ความงามครบวงจร',
            'employees' => $employees
        ];
        $this->view('employee/index', $data);
    }

    public function add() {
        $positions = $this->employeeModel->getPositions();
        $data = [
            'title' => 'เพิ่มข้อมูลพนักงาน | Mira ศูนย์ความงามครบวงจร',
            'positions' => $positions
        ];
        $this->view('employee/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $empId = $this->employeeModel->insertEmployee($_POST);
            if ($empId) {
                $this->employeeModel->updateEmployeePositions($empId, $_POST['position'] ?? []);
                redirect()->with('success', 'เพิ่มข้อมูลพนักงานสำเร็จ')->to('/employee');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูลพนักงาน')->back();
            }
        }
    }

    public function edit($id) {
        $employee = $this->employeeModel->getEmployeeById($id);
        if (!$employee) {
            redirect()->with('error', 'ไม่พบข้อมูลพนักงาน')->back();
        }
        $positions = $this->employeeModel->getPositions();
        $selectedPositions = $this->employeeModel->getEmployeePositions($id);
        $data = [
            'title' => 'แก้ไขข้อมูลพนักงาน | Mira ศูนย์ความงามครบวงจร',
            'employee' => $employee,
            'positions' => $positions,
            'selectedPositions' => $selectedPositions
        ];
        $this->view('employee/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->employeeModel->updateEmployee($_POST);
            if ($result) {
                $this->employeeModel->updateEmployeePositions($_POST['emp_id'], $_POST['position'] ?? []);
                redirect()->with('success', 'อัปเดตข้อมูลพนักงานสำเร็จ')->to('/employee');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูลพนักงาน')->back();
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->employeeModel->deleteEmployee($id);
            if ($result) {
                redirect()->with('success', 'ลบข้อมูลพนักงานสำเร็จ')->to('/employee');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูลพนักงาน')->back();
            }
        }
    }
}