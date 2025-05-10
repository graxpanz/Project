<?php
class StockController extends Controller {
    private $stockMovementModel;
    private $supplyModel;

    public function __construct() {
        parent::__construct();
        $this->stockMovementModel = $this->model('StockMovement');
        $this->supplyModel = $this->model('Supply');
    }

    public function index() {
        $movements = $this->stockMovementModel->getAllMovements();
        $data = [
            'title' => 'จัดการคลังสินค้า | Mira ศูนย์ความงามครบวงจร',
            'movements' => $movements,
        ];
        $this->view('stock/index', $data);
    }

    public function add() {
        $supplies = $this->supplyModel->getAllSupplies();
        $data = [
            'title' => 'เพิ่มรายการเคลื่อนไหวสต็อก | Mira ศูนย์ความงามครบวงจร',
            'supplies' => $supplies,
        ];
        $this->view('stock/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd ถ้าจำเป็น
            if (isset($_POST['movement_date']) && strpos($_POST['movement_date'], '/') !== false) {
                $date_parts = explode('/', $_POST['movement_date']);
                if (count($date_parts) === 3) {
                    $_POST['movement_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                }
            }
            
            if ($this->stockMovementModel->insertMovement($_POST)) {
                redirect()->with('success', 'เพิ่มรายการเคลื่อนไหวสต็อกสำเร็จ')->to('/stock');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มรายการ')->back();
            }
        }
    }

    public function edit($id) {
        $movement = $this->stockMovementModel->getMovementById($id);
        if (!$movement) {
            redirect()->with('error', 'ไม่พบรายการเคลื่อนไหวสต็อก')->to('/stock');
        }
        
        $supplies = $this->supplyModel->getAllSupplies();
        
        $data = [
            'title' => 'แก้ไขรายการเคลื่อนไหวสต็อก | Mira ศูนย์ความงามครบวงจร',
            'movement' => $movement,
            'supplies' => $supplies,
        ];
        $this->view('stock/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // แปลงวันที่จาก dd/mm/yyyy เป็น yyyy-mm-dd ถ้าจำเป็น
            if (isset($_POST['movement_date']) && strpos($_POST['movement_date'], '/') !== false) {
                $date_parts = explode('/', $_POST['movement_date']);
                if (count($date_parts) === 3) {
                    $_POST['movement_date'] = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
                }
            }
            
            if ($this->stockMovementModel->updateMovement($_POST)) {
                redirect()->with('success', 'อัปเดตรายการเคลื่อนไหวสต็อกสำเร็จ')->to('/stock');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตรายการ')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->stockMovementModel->deleteMovement($id)) {
            redirect()->with('success', 'ลบรายการเคลื่อนไหวสต็อกสำเร็จ')->to('/stock');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบรายการ')->back();
        }
    }
    
    public function report() {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        
        $movements = $this->stockMovementModel->getMovementsByDateRange($start_date, $end_date);
        $supplies = $this->supplyModel->getAllSupplies();
        
        $data = [
            'title' => 'รายงานการเคลื่อนไหวสต็อก | Mira ศูนย์ความงามครบวงจร',
            'movements' => $movements,
            'supplies' => $supplies,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $this->view('stock/report', $data);
    }
    
    public function stockIn() {
        $supplies = $this->supplyModel->getAllSupplies();
        $data = [
            'title' => 'รับเข้าสต็อก | Mira ศูนย์ความงามครบวงจร',
            'supplies' => $supplies,
            'movement_type' => 'in'
        ];
        $this->view('stock/stock_in', $data);
    }
    
    public function stockOut() {
        $supplies = $this->supplyModel->getAllSupplies();
        $data = [
            'title' => 'เบิกออกสต็อก | Mira ศูนย์ความงามครบวงจร',
            'supplies' => $supplies,
            'movement_type' => 'out'
        ];
        $this->view('stock/stock_out', $data);
    }
}