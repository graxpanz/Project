<?php
class Promotion {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllPromotions() {
        $sql = "SELECT * FROM promotion";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionById($id) {
        $sql = "SELECT * FROM promotion WHERE promotion_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertPromotion($data) {
        $sql = "INSERT INTO promotion (promotion_name, detail, date_start, date_end) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['promotion_name'],
            $data['detail'],
            $data['date_start'],
            $data['date_end']
        ]);
    }

    public function updatePromotion($data) {
        $sql = "UPDATE promotion SET 
                promotion_name = ?, 
                detail = ?, 
                date_start = ?, 
                date_end = ? 
                WHERE promotion_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['promotion_name'],
            $data['detail'],
            $data['date_start'],
            $data['date_end'],
            $data['promotion_id']
        ]);
    }

    public function deletePromotion($id) {
        $sql = "DELETE FROM promotion WHERE promotion_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}