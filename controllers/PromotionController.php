<?php
class PromotionController extends Controller {
    private $promotionModel;

    public function __construct() {
        parent::__construct();
        $this->promotionModel = $this->model('Promotion');
    }

    public function index() {
        $promotions = $this->promotionModel->getAllPromotions();
        $data = [
            'title' => 'จัดการโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'promotions' => $promotions
        ];
        $this->view('promotion/index', $data);
    }

    public function add() {
        $data = [
            'title' => 'เพิ่มข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร'
        ];
        $this->view('promotion/add', $data);
    }

    public function insert() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->promotionModel->insertPromotion($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id) {
        $promotion = $this->promotionModel->getPromotionById($id);
        if (!$promotion) {
            redirect()->with('error', 'ไม่พบข้อมูลโปรโมชั่น')->to('/promotion');
        }

        $data = [
            'title' => 'แก้ไขข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'promotion' => $promotion
        ];
        $this->view('promotion/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->promotionModel->updatePromotion($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->promotionModel->deletePromotion($id)) {
            redirect()->with('success', 'ลบข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}