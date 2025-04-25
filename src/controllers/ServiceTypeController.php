<?php
class ServiceTypeController extends Controller {
    private $serviceTypeModel;

    public function __construct() {
        parent::__construct();
        $this->serviceTypeModel = $this->model('ServiceType');
    }

    public function index() {
        $service_types = $this->serviceTypeModel->getAllServiceTypes();
        $data = [
            'title' => 'จัดการประเภทของบริการ | Mira ศูนย์ความงามครบวงจร',
            'service_types' => $service_types
        ];
        $this->view('service_type/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มข้อมูลประเภทของบริการ | Mira ศูนย์ความงามครบวงจร',
        ];
        $this->view('service_type/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->serviceTypeModel->insertServiceType($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลประเภทของบริการสำเร็จ')->to('/service_type');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเเพิ่มข้อมูลประเภทของบริการ')->back();
            }
        }
    }

    public function edit($id) {
        $service_type = $this->serviceTypeModel->getServiceTypeById($id);
        if (!$service_type) {
            redirect()->with('error', 'ไม่พบข้อมูลประเภทของบริการ')->to('/service_type');
        }
    
        $data = [
            'title' => 'แก้ไขข้อมูลประเภทของบริการ | Mira ศูนย์ความงามครบวงจร',
            'service_type' => $service_type,
        ];
        $this->view('service_type/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->serviceTypeModel->updateServiceType($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลประเภทของบริการสำเร็จ')->to('/service_type');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->serviceTypeModel->deleteServiceType($id)) {
            redirect()->with('success', 'ลบข้อมูลประเภทของบริการสำเร็จ')->to('/service_type');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}