<?php
class EstimateController extends Controller {
    private $estimateModel;

    public function __construct() {
        parent::__construct();
        $this->estimateModel = $this->model('Estimate');
    }

    public function index() {
        $estimates = $this->estimateModel->getAllEstimates();
        $data = [
            'title' => 'การประเมินใบหน้า | Mira ศูนย์ความงามครบวงจร',
            'estimates' => $estimates
        ];
        $this->view('estimate/index', $data);
    }

    public function detail($id) {
        $estimate = $this->estimateModel->getEstimateById($id);
        if (!$estimate) {
            redirect()->with('error', 'ไม่พบข้อมูลการประเมิน')->to('/estimate');
        }

        $data = [
            'title' => 'รายละเอียดการประเมินใบหน้า | Mira ศูนย์ความงามครบวงจร',
            'estimate' => $estimate
        ];
        $this->view('estimate/detail', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate input
            $data = [
                'estimate_id' => $_POST['estimate_id'],
                'response' => trim($_POST['response']),
                'price' => trim($_POST['price']),
                'date' => $_POST['date'],
                'status' => $_POST['status']
            ];

            if ($this->estimateModel->updateEstimate($data)) {
                redirect()->with('success', 'อัปเดตข้อมูลการประเมินสำเร็จ')->to('/estimate');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }
}