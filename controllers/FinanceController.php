<?php
class FinanceController extends Controller {
    private $financeModel;

    public function __construct() {
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login');
        }
        $this->financeModel = $this->model('Finance');
    }

    public function index() {
        $finances = $this->financeModel->getAllFinances();
        $summary = $this->financeModel->getSummary();
        
        $data = [
            'title' => 'จัดการข้อมูลการเงิน | Mira ศูนย์ความงามครบวงจร',
            'finances' => $finances,
            'summary' => $summary
        ];
        $this->view('finance/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มข้อมูลการเงิน | Mira ศูนย์ความงามครบวงจร'
        ];
        $this->view('finance/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->financeModel->insertFinance($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลการเงินสำเร็จ')->to('/finance');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id) {
        $finance = $this->financeModel->getFinanceById($id);
        if (!$finance) {
            redirect()->with('error', 'ไม่พบข้อมูลการเงิน')->to('/finance');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลการเงิน | Mira ศูนย์ความงามครบวงจร',
            'finance' => $finance
        ];
        $this->view('finance/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->financeModel->updateFinance($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลการเงินสำเร็จ')->to('/finance');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->financeModel->deleteFinance($id)) {
            redirect()->with('success', 'ลบข้อมูลการเงินสำเร็จ')->to('/finance');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}