<?php
class Estimate
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllEstimates()
    {
        $sql = "SELECT e.estimate_id, c.name, e.file, e.date, e.status, e.price 
                FROM estimate e 
                JOIN customer c ON e.customer_id = c.customer_id";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstimateById($id)
    {
        $sql = "SELECT e.*, c.name 
                FROM estimate e 
                JOIN customer c ON e.customer_id = c.customer_id 
                WHERE e.estimate_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateEstimate($data)
    {
        $sql = "UPDATE estimate 
                SET response = ?, 
                    price = ?,
                    date = ?, 
                    status = ?
                WHERE estimate_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['response'],
            $data['price'],
            $data['date'],
            $data['status'],
            $data['estimate_id']
        ]);
    }
}
