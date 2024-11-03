<?php
class StockController extends Controller {
    private $stockModel;

    public function __construct() {
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login'); 
        }
        $this->stockModel = $this->model('Stock');
    }

    public function index() {
        $stock = $this->stockModel->getAllStock();
        $data = [
            'title' => 'จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน | Mira ศูนย์ความงามครบวงจร',
            'stock' => $stock
        ];
        $this->view('stock/index', $data);
    }

    public function add() {
        $stocks = $this->stockModel->getStock();
        $data = [
            'title' => 'เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน | Mira ศูนย์ความงามครบวงจร',
            'stocks' => $stocks
        ];
        $this->view('stock/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->stockModel->insertStock($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลสินค้าและผลิตภัณฑ์ภายในร้านสำเร็จ')->to('/stock');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id) {
        $stock = $this->stockModel->getStockById($id);
        $stockTypes = $this->stockModel->getStockTypes();
        $data = [
            'title' => 'แก้ไขข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน | Mira ศูนย์ความงามครบวงจร',
            'stock' => $stock,
            'stockTypes' => $stockTypes
        ];
        $this->view('stock/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->stockModel->updateStock($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน')->to('/stock');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->stockModel->deleteStock($id)) {
            redirect()->with('success', 'ลบข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน')->to('/stock');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}