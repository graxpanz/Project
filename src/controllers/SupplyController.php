<?php
class SupplyController extends Controller
{
    private $supplyModel;
    private $stockMovementModel;

    public function __construct()
    {
        parent::__construct();
        $this->supplyModel = $this->model('Supply');
        $this->stockMovementModel = $this->model('StockMovement');
    }

    public function index()
    {
        $supplies = $this->supplyModel->getAllSupplies();
        $data = [
            'title' => 'จัดการวัสดุสิ้นเปลือง | Mira ศูนย์ความงามครบวงจร',
            'supplies' => $supplies,
        ];
        $this->view('supply/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'เพิ่มข้อมูลวัสดุสิ้นเปลือง | Mira ศูนย์ความงามครบวงจร'
        ];
        $this->view('supply/add', $data);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->supplyModel->insertSupply($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลวัสดุสิ้นเปลืองสำเร็จ')->to('/supply');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id)
    {
        $supply = $this->supplyModel->getSupplyById($id);
        if (!$supply) {
            redirect()->with('error', 'ไม่พบข้อมูลวัสดุสิ้นเปลือง')->to('/supply');
        }
        $data = [
            'title' => 'แก้ไขข้อมูลวัสดุสิ้นเปลือง | Mira ศูนย์ความงามครบวงจร',
            'supply' => $supply,
        ];
        $this->view('supply/edit', $data);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->supplyModel->updateSupply($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลวัสดุสิ้นเปลืองสำเร็จ')->to('/supply');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id)
    {
        if ($this->supplyModel->deleteSupply($id)) {
            redirect()->with('success', 'ลบข้อมูลวัสดุสิ้นเปลืองสำเร็จ')->to('/supply');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function lowStock()
    {
        $supplies = $this->supplyModel->getLowStockSupplies();
        $data = [
            'title' => 'วัสดุสิ้นเปลืองที่ใกล้หมด | Mira ศูนย์ความงามครบวงจร',
            'supplies' => $supplies,
        ];
        $this->view('supply/low_stock', $data);
    }
}
