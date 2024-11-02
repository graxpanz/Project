<?php
class Manager {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllManagers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getManagerById($id) {
        $sql = "SELECT * FROM users WHERE u_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertManager($data) {
        $sql = "INSERT INTO users (firstname, lastname, username, password, status, image) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        
        // จัดการรูปภาพ (ถ้ามี)
        $image = isset($_FILES['file']) ? $this->uploadImage($_FILES['file']) : 'default.png';
        
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['password'], // ควรใช้ password_hash() ในการเข้ารหัส
            $data['status'],
            $image
        ]);
    }

    public function updateManager($data) {
        $sql = "UPDATE users SET 
                firstname = ?, 
                lastname = ?, 
                username = ?, 
                status = ?";
        
        $params = [
            $data['firstname'],
            $data['lastname'],
            $data['username'],
            $data['status']
        ];

        // เพิ่ม password ถ้ามีการส่งมา
        if (!empty($data['password'])) {
            $sql .= ", password = ?";
            $params[] = $data['password']; // ควรใช้ password_hash()
        }

        // เพิ่ม image ถ้ามีการอัพโหลดไฟล์ใหม่
        if (isset($_FILES['file'])) {
            $image = $this->uploadImage($_FILES['file']);
            $sql .= ", image = ?";
            $params[] = $image;
        }

        $sql .= " WHERE u_id = ?";
        $params[] = $data['u_id'];

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteManager($id) {
        $sql = "DELETE FROM users WHERE u_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    private function uploadImage($file) {
        // จัดการอัพโหลดรูปภาพ
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_name = uniqid() . '.' . $extension;
        $upload_path = 'path/to/upload/directory/' . $new_name;
        
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return $new_name;
        }
        return 'default.png';
    }
}