<?php
class ServiceController extends Controller {
    private $serviceModel;

    public function __construct() {
        parent::__construct();
        $this->serviceModel = $this->model('Service');
    }

    public function index() {
        $services = $this->serviceModel->getAllServices();
        $serviceTypes = $this->serviceModel->getServiceTypes();
        $data = [
            'title' => 'จัดการการบริการ | Mira ศูนย์ความงามครบวงจร',
            'services' => $services,
            'serviceTypes' => $serviceTypes
        ];
        $this->view('service/index', $data);
    }

    public function add() {
        $serviceTypes = $this->serviceModel->getServiceTypes();
        $data = [
            'title' => 'เพิ่มข้อมูลการบริการ | Mira ศูนย์ความงามครบวงจร',
            'serviceTypes' => $serviceTypes
        ];
        $this->view('service/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->serviceModel->insertService($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลการบริการสำเร็จ')->to('/service');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id) {
        $service = $this->serviceModel->getServiceById($id);
        if (!$service) {
            redirect()->with('error', 'ไม่พบข้อมูลการบริการ')->to('/service');
        }
        $serviceTypes = $this->serviceModel->getServiceTypes();
        $data = [
            'title' => 'แก้ไขข้อมูลการบริการ | Mira ศูนย์ความงามครบวงจร',
            'service' => $service,
            'serviceTypes' => $serviceTypes
        ];
        $this->view('service/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->serviceModel->updateService($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลการบริการสำเร็จ')->to('/service');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->serviceModel->deleteService($id)) {
            redirect()->with('success', 'ลบข้อมูลการบริการสำเร็จ')->to('/service');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}