<?php
class Service {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllServices() {
        $sql = "SELECT service.service_id, service.service_name, service.service_type_id, 
                       service.service_price, service.service_time, service.service_detail, 
                       service_type.service_type_name 
                FROM service 
                JOIN service_type ON service.service_type_id = service_type.service_type_id";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceTypes() {
        $sql = "SELECT service_type_id, service_type_name FROM service_type";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceById($id) {
        $sql = "SELECT * FROM service WHERE service_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertService($data) {
        $sql = "INSERT INTO service (service_name, service_type_id, service_price, service_time, service_detail) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['service_name'],
            $data['service_type_id'],
            $data['service_price'],
            $data['service_time'],
            $data['service_detail']
        ]);
    }

    public function updateService($data) {
        $sql = "UPDATE service SET 
                service_name = ?, 
                service_type_id = ?, 
                service_price = ?, 
                service_time = ?, 
                service_detail = ? 
                WHERE service_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['service_name'],
            $data['service_type_id'],
            $data['service_price'],
            $data['service_time'],
            $data['service_detail'],
            $data['service_id']
        ]);
    }

    public function deleteService($id) {
        $sql = "DELETE FROM service WHERE service_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}