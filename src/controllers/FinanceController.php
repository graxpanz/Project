<?php
class FinanceController extends Controller {
    private $financeModel;

    public function __construct() {
        parent::__construct();
        $this->financeModel = $this->model('Finance');
    }

    public function index() {
        $finances = $this->financeModel->getAllFinances();
        
        // คำนวณผลรวมรายรับ-รายจ่าย
        $total_income = 0;
        $total_outcome = 0;
        
        foreach ($finances as $finance) {
            $total_income += $finance['income'];
            $total_outcome += $finance['outcome'];
        }
        
        $balance = $total_income - $total_outcome;
        
        $data = [
            'title' => 'จัดการรายรับ-รายจ่าย | Mira ศูนย์ความงามครบวงจร',
            'finances' => $finances,
            'total_income' => $total_income,
            'total_outcome' => $total_outcome,
            'balance' => $balance
        ];
        
        $this->view('finance/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มรายการรายรับ-รายจ่าย | Mira ศูนย์ความงามครบวงจร'
        ];
        $this->view('finance/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd ถ้าจำเป็น
            if (isset($_POST['transaction_date']) && strpos($_POST['transaction_date'], '/') !== false) {
                $date_parts = explode('/', $_POST['transaction_date']);
                if (count($date_parts) === 3) {
                    $_POST['transaction_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                }
            }
            
            if ($this->financeModel->insertFinance($_POST)) {
                redirect()->with('success', 'เพิ่มรายการรายรับ-รายจ่ายสำเร็จ')->to('/finance');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มรายการ')->back();
            }
        }
    }

    public function edit($id) {
        $finance = $this->financeModel->getFinanceById($id);
        if (!$finance) {
            redirect()->with('error', 'ไม่พบรายการรายรับ-รายจ่าย')->to('/finance');
        }
        
        $data = [
            'title' => 'แก้ไขรายการรายรับ-รายจ่าย | Mira ศูนย์ความงามครบวงจร',
            'finance' => $finance
        ];
        
        $this->view('finance/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd ถ้าจำเป็น
            if (isset($_POST['transaction_date']) && strpos($_POST['transaction_date'], '/') !== false) {
                $date_parts = explode('/', $_POST['transaction_date']);
                if (count($date_parts) === 3) {
                    $_POST['transaction_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                }
            }
            
            if ($this->financeModel->updateFinance($_POST)) {
                redirect()->with('success', 'อัปเดตรายการรายรับ-รายจ่ายสำเร็จ')->to('/finance');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตรายการ')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->financeModel->deleteFinance($id)) {
            redirect()->with('success', 'ลบรายการรายรับ-รายจ่ายสำเร็จ')->to('/finance');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบรายการ')->back();
        }
    }
    
    public function report() {
        $current_year = date('Y');
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        
        $finances = $this->financeModel->getFinancesByDateRange($start_date, $end_date);
        $summary = $this->financeModel->getSummaryByDateRange($start_date, $end_date);
        $monthly_summary = $this->financeModel->getMonthlySummary($current_year);
        
        $data = [
            'title' => 'รายงานรายรับ-รายจ่าย | Mira ศูนย์ความงามครบวงจร',
            'finances' => $finances,
            'summary' => $summary,
            'monthly_summary' => $monthly_summary,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'current_year' => $current_year
        ];
        
        $this->view('finance/report', $data);
    }
}