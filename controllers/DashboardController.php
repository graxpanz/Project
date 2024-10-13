<?php
class DashboardController extends Controller {
    private $queueModel;

    public function __construct() {
        if (!isset($_SESSION['AD_ID'])) {
            redirect('/login'); 
        }
        
        $this->queueModel = $this->model('Queue');
    }

    public function index() {
        $queues = $this->queueModel->getAllQueues();
        $pendingCount = $this->queueModel->getCountByStatus('Pending');
        $confirmedCount = $this->queueModel->getCountByStatus('Confirmed');
        $completedCount = $this->queueModel->getCountByStatus('Completed');
        $cancelledCount = $this->queueModel->getCountByStatus('Cancelled');

        $data = [
            'title' => 'หน้าหลัก | Mira ศูนย์ความงามครบวงจร',
            'queues' => $queues,
            'pendingCount' => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount
        ];
    
        $this->view('dashboard/index', $data);
    }

    public function detailQueue($id)
    {
        $queue = $this->queueModel->getQueueById($id);

        if (!$queue) {
            redirect()->with('error', 'ไม่พบข้อมูลการจอง')->back();
        }

        $data = [
            'title' => 'รายละเอียดการจอง | Mira ศูนย์ความงามครบวงจร',
            'queue' => $queue
        ];

        $this->view('dashboard/detail_queue', $data);
    }
}