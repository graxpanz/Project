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
}
