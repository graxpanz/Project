<?php
class Stock
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllStock()
    {
        $sql = "
        SELECT 
            stock.stock_id, 
            stock.stock_name, 
            stock.service_type_id, 
            stock.amount, 
            stock.image, 
            stock.is_active, 
            stock.deleted_at, 
            stock.created_at, 
            stock.updated_at, 
            service_type.service_type_name 
        FROM 
            stock 
        JOIN 
            service_type ON stock.service_type_id = service_type.service_type_id
        ";

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getStockTypes() {
        $sql = "SELECT service_type_name FROM service_type";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStockById($id) {
        $sql = "SELECT * FROM stock WHERE stock_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertStock($data) {
        $sql = "INSERT INTO stock (stock_name, service_type_id, amount, image) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['stock_name'],
            $data['service_type_id'],
            $data['amount'],
            $data['image']
        ]);
    }

    public function updateStock($data) {
        $sql = "UPDATE stock SET 
                stock_name = ?, 
                service_type_id = ?, 
                amount = ?, 
                image = ?
                WHERE stock_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['stock_name'],
            $data['service_type_id'],
            $data['amount'],
            $data['image'],
            $data['stock_id']
        ]);
    }

    public function deleteStock($id) {
        $sql = "DELETE FROM stock WHERE stock_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
