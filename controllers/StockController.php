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

    
}