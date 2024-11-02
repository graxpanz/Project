<?php
class Role {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllroles() {
        $sql = "SELECT * FROM role";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id) {
        $sql = "SELECT * FROM role WHERE role_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertRole($data) {
        $sql = "INSERT INTO role (name, permission, is_active) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['name'],
            implode(", ", $data['permissions']),
            $data['is_active'] ?? 0,
        ]);
    }

    public function updateRole($data) {
        $sql = "UPDATE role SET 
                name = ?, 
                permission = ?,
                is_active = ?
                WHERE role_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['name'],
            implode(", ", $data['permissions']),
            $data['is_active'] ?? 0,
            $data['role_id']
        ]);
    }

    public function deleteRole($id) {
        $sql = "DELETE FROM role WHERE role_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}