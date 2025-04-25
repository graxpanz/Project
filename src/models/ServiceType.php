<?php
class ServiceType
{
    private $db;
    private $dbname = 'service_type';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllServiceTypes()
    {
        $sql = "SELECT * FROM $this->dbname WHERE deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceTypeById($id)
    {
        $sql = "SELECT * FROM $this->dbname WHERE service_type_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertServiceType($data)
    {
        $sql = "INSERT INTO $this->dbname (
                name, description, is_active, created_at, updated_at
            ) VALUES (?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['is_active'] ?? 1
        ]);
    }

    public function updateServiceType($data)
    {
        $sql = "UPDATE $this->dbname SET 
        name = ?,
        description = ?,
        is_active = ?
        WHERE service_type_id = ? AND deleted_at IS NULL";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['is_active'],
            $data['service_type_id']
        ]);
    }

    public function deleteServiceType($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE service_type_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE service_type_id = ?";
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE $this->dbname SET 
                is_active = ?,
                updated_at = NOW() 
                WHERE user_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$status, $id]);
    }
}
