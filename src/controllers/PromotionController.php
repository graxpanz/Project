<?php
class PromotionController extends Controller
{
    private $promotionModel;

    public function __construct()
    {
        parent::__construct();
        $this->promotionModel = $this->model('Promotion');
    }

    public function index()
    {
        $promotions = $this->promotionModel->getAllPromotions();
        $data = [
            'title' => 'จัดการข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
            'promotions' => $promotions,
        ];
        $this->view('promotion/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'เพิ่มข้อมูลโปรโมชั่น | Mira ศูนย์ความงามครบวงจร',
        ];
        $this->view('promotion/add', $data);
    }

    public function insert()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->promotionModel->insertPromotion($_POST)) {
                redirect()->with('success', 'เพิ่มข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล')->back();
            }
        }
    }

    public function edit($id)
    {
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

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->promotionModel->updatePromotion($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id)
    {
        if ($this->promotionModel->deletePromotion($id)) {
            redirect()->with('success', 'ลบข้อมูลโปรโมชั่นสำเร็จ')->to('/promotion');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }

    public function api_promotion()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        try {
            $services = $this->promotionModel->getCurrentPromotions();
            foreach ($services as $key => $service) {
                $services[$key]['image'] = $service['image'] ? "assets/uploads/promotion/" . $service['image'] : null;
            }
            $this->json([
                'status' => true,
                'message' => 'Promotions data retrieved successfully',
                'data' => $services
            ]);
        } catch (Exception $e) {
            error_log("API service error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve promotions data',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function api_redeem()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json([
                'status' => false,
                'message' => 'Invalid request method'
            ]);
        }
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['customer_id']) || !isset($data['code'])) {
                $this->json([
                    'status' => false,
                    'message' => 'Missing required parameter: customer_id and code'
                ]);
                return;
            }


            $promotion = $this->promotionModel->getPromotionRedeem($data);
            if ($promotion) {
                $promotion['image'] = $promotion['image'] ? "assets/uploads/promotion/" . $promotion['image'] : null;
                if ($promotion['already_used']) {
                    $this->json([
                        'status' => false,
                        'message' => 'This promotion code has already been used',
                        'data' => false
                    ]);
                } else {
                    $this->json([
                        'status' => true,
                        'message' => 'Promotion data retrieved successfully',
                        'data' => $promotion
                    ]);
                }
            } else {
                $this->json([
                    'status' => false,
                    'message' => 'Promotion not found',
                    'data' => false
                ]);
            }
        } catch (Exception $e) {
            error_log("API promotion error: " . $e->getMessage());
            $this->json([
                'status' => false,
                'message' => 'Failed to retrieve promotion data',
                'error' => $e->getMessage()
            ]);
        }
    }
}
