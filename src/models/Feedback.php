<?php
class Feedback
{
    private $db;
    private $dbname = 'feedback';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllFeedback()
    {
        $sql = "SELECT f.*, c.firstname as customer_firstname, c.lastname as customer_lastname, 
                s.name as service_name 
                FROM $this->dbname f 
                LEFT JOIN customer c ON f.customer_id = c.customer_id 
                LEFT JOIN service s ON f.service_id = s.service_id 
                WHERE f.deleted_at IS NULL 
                ORDER BY f.created_at DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeedbackById($id)
    {
        $sql = "SELECT f.*, c.firstname as customer_firstname, c.lastname as customer_lastname, 
                s.name as service_name 
                FROM $this->dbname f 
                LEFT JOIN customer c ON f.customer_id = c.customer_id 
                LEFT JOIN service s ON f.service_id = s.service_id 
                WHERE f.feedback_id = ? AND f.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeedbackByCustomerId($customerId)
    {
        $sql = "SELECT f.*, s.name as service_name 
                FROM $this->dbname f 
                LEFT JOIN service s ON f.service_id = s.service_id 
                WHERE f.customer_id = ? AND f.deleted_at IS NULL 
                ORDER BY f.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFeedbackByServiceId($serviceId)
    {
        $sql = "SELECT f.*, c.firstname as customer_firstname, c.lastname as customer_lastname 
                FROM $this->dbname f 
                LEFT JOIN customer c ON f.customer_id = c.customer_id 
                WHERE f.service_id = ? AND f.deleted_at IS NULL 
                ORDER BY f.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$serviceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertFeedback($data)
    {
        $sql = "INSERT INTO $this->dbname (
                customer_id, service_id, rating, comment, is_active,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([
            $data['customer_id'],
            $data['service_id'] ?? null,
            $data['rating'],
            $data['comment'] ?? null,
            $data['is_active'] ?? '1'
        ]);
    }

    public function updateFeedback($data)
    {
        // ตรวจสอบว่ามีข้อมูล feedback อยู่จริง
        $currentFeedback = $this->getFeedbackById($data['feedback_id']);
        if (!$currentFeedback) {
            return false;
        }

        $sql = "UPDATE $this->dbname SET 
            customer_id = ?,
            service_id = ?,
            rating = ?,
            comment = ?,
            is_active = ?,
            updated_at = NOW()
            WHERE feedback_id = ?";

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['customer_id'],
            $data['service_id'] ?? null,
            $data['rating'],
            $data['comment'] ?? null,
            $data['is_active'],
            $data['feedback_id']
        ]);
    }

    public function deleteFeedback($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE feedback_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE feedback_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE $this->dbname SET 
                is_active = ?,
                updated_at = NOW() 
                WHERE feedback_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getFeedbackStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_feedback,
                AVG(rating) as average_rating,
                COUNT(CASE WHEN rating = '5' THEN 1 END) as five_star,
                COUNT(CASE WHEN rating = '4' THEN 1 END) as four_star,
                COUNT(CASE WHEN rating = '3' THEN 1 END) as three_star,
                COUNT(CASE WHEN rating = '2' THEN 1 END) as two_star,
                COUNT(CASE WHEN rating = '1' THEN 1 END) as one_star
                FROM $this->dbname
                WHERE deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServiceFeedbackStats()
    {
        $sql = "SELECT 
                s.service_id,
                s.name as service_name,
                COUNT(f.feedback_id) as total_feedback,
                AVG(f.rating) as average_rating
                FROM service s
                LEFT JOIN $this->dbname f ON s.service_id = f.service_id AND f.deleted_at IS NULL
                GROUP BY s.service_id
                ORDER BY average_rating DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // สถิติคะแนนความพึงพอใจ
    public function getRatingStats()
    {
        try {
            $sql = "SELECT rating, COUNT(*) as count
                FROM feedback
                WHERE deleted_at IS NULL
                GROUP BY rating
                ORDER BY rating";
            $stmt = $this->db->getConnection()->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stats = ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, 'average' => 0, 'total' => 0];
            $total_ratings = 0;
            $rating_sum = 0;

            foreach ($result as $row) {
                $stats[$row['rating']] = $row['count'];
                $total_ratings += $row['count'];
                $rating_sum += $row['rating'] * $row['count'];
            }

            $stats['total'] = $total_ratings;
            $stats['average'] = $total_ratings > 0 ? round($rating_sum / $total_ratings, 1) : 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting feedback stats: " . $e->getMessage());
            return ['1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, 'average' => 0, 'total' => 0];
        }
    }

    // ความคิดเห็นล่าสุด
    public function getRecentFeedback($limit = 5)
    {
        try {
            $sql = "SELECT f.*, 
                c.firstname as customer_firstname, c.lastname as customer_lastname,
                s.name as service_name
                FROM feedback f
                LEFT JOIN customer c ON f.customer_id = c.customer_id
                LEFT JOIN service s ON f.service_id = s.service_id
                WHERE f.deleted_at IS NULL
                ORDER BY f.created_at DESC
                LIMIT ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting recent feedback: " . $e->getMessage());
            return [];
        }
    }
}
