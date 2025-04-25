<?php
class BookingController extends Controller {
    private $serviceModel;
    private $serviceTypeModel;

    public function __construct() {
        parent::__construct();
        $this->serviceModel = $this->model('Service');
        $this->serviceTypeModel = $this->model('ServiceType');
    }

    public function index() {
        $services = $this->serviceModel->getAllServices();
        $data = [
            'title' => 'จัดการข้อมูลการบริการ | Mira ศูนย์ความงามครบวงจร',
            'services' => $services,
        ];
        $this->view('service/index', $data);
    }

    public function add() {
        $service_types = $this->serviceTypeModel->getAllServiceTypes();
        $data = [
            'title' => 'เพิ่มข้อมูลการบริการ | Mira ศูนย์ความงามครบวงจร',
            'service_types' => $service_types
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
        $service_types = $this->serviceTypeModel->getAllServiceTypes();
        $data = [
            'title' => 'แก้ไขข้อมูลการบริการ | Mira ศูนย์ความงามครบวงจร',
            'service' => $service,
            'service_types' => $service_types
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

    public function api_service() {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        try {
            $services = $this->serviceModel->getAllServices();
            foreach ($services as $key => $service) {
                $services[$key]['image'] = $service['image'] ? "assets/uploads/service/" . $service['image'] : null;
            }
            $this->json([
                'status' => true,
                'message' => 'Services data retrieved successfully',
                'data' => $services
            ]);

        } catch (Exception $e) {
            error_log("API service error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve services data',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_service_by_id($id) {
        if ($_SERVER['REQUEST_METHOD'] != 'GET' && !$id) {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        try {
            $service = $this->serviceModel->getServiceById($id);
            if ($service) {
                $service['image'] = $service['image'] ? "assets/uploads/service/" . $service['image'] : null;
            }
            $this->json([
                'status' => true,
                'message' => 'Service data retrieved successfully',
                'data' => $service
            ]);

        } catch (Exception $e) {
            error_log("API service error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve service data',
                'error' => $e->getMessage()
            ]);
        }
    }
}