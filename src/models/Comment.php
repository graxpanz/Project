<?php
class Comment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllComments() {
        $sql = "SELECT * FROM comment ORDER BY date DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentById($id) {
        $sql = "SELECT * FROM comment WHERE comment_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateComment($data) {
        $sql = "UPDATE comment SET response = ?, date = ? WHERE comment_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['response'],
            $data['date'],
            $data['comment_id']
        ]);
    }

    public function deleteComment($id) {
        $sql = "DELETE FROM comment WHERE comment_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}