<?php
class Role {
    private $db;
    private $dbname = 'user_role';
    private $soft_delete = true;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllroles() {
        $sql = "SELECT * FROM $this->dbname WHERE deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id) {
        $sql = "SELECT * FROM $this->dbname WHERE user_role_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertRole($data) {
        $sql = "INSERT INTO $this->dbname (name, permission, is_active) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['name'],
            implode(", ", $data['permissions']),
            $data['is_active'] ?? 0,
        ]);
    }

    public function updateRole($data) {
        $sql = "UPDATE $this->dbname SET 
                name = ?, 
                permission = ?,
                is_active = ?
                WHERE user_role_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['name'],
            implode(", ", $data['permissions']),
            $data['is_active'] ?? 0,
            $data['user_role_id']
        ]);
    }

    public function deleteRole($id) {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET deleted_at = NOW() WHERE user_role_id = ?";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE user_role_id = ?";
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}