<?php
class CommentController extends Controller {
    private $commentModel;

    public function __construct() {
        parent::__construct();
        $this->commentModel = $this->model('Comment');
    }

    public function index() {
        $comments = $this->commentModel->getAllComments();
        $data = [
            'title' => 'จัดการความคิดเห็น | Mira ศูนย์ความงามครบวงจร',
            'comments' => $comments
        ];
        $this->view('comment/index', $data);
    }

    public function detail($id) {
        $comment = $this->commentModel->getCommentById($id);
        if (!$comment) {
            redirect()->with('error', 'ไม่พบข้อมูลความคิดเห็น')->to('/comment');
        }

        $data = [
            'title' => 'รายละเอียดความคิดเห็น | Mira ศูนย์ความงามครบวงจร',
            'comment' => $comment
        ];
        $this->view('comment/detail', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->commentModel->updateComment($_POST)) {
                redirect()->with('success', 'อัปเดตข้อมูลความคิดเห็นสำเร็จ')->to('/comment');
            } else {
                redirect()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล')->back();
            }
        }
    }

    public function delete($id) {
        if ($this->commentModel->deleteComment($id)) {
            redirect()->with('success', 'ลบข้อมูลความคิดเห็นสำเร็จ')->to('/comment');
        } else {
            redirect()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล')->back();
        }
    }
}