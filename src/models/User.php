<?php
class User
{
    private $db;
    private $dbname = 'user';
    private $uploadPath = 'assets/uploads/user/';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findByUsername($username)
    {
        $sql = "SELECT u.*, r.permission as permission
                FROM $this->dbname u 
                LEFT JOIN user_role r ON u.user_role_id = r.user_role_id 
                WHERE u.username = ? AND u.is_active = '1' AND u.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM $this->dbname u 
                LEFT JOIN user_role r ON u.user_role_id = r.user_role_id 
                WHERE u.deleted_at IS NULL 
                ORDER BY u.created_at ASC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM $this->dbname u 
                LEFT JOIN user_role r ON u.user_role_id = r.user_role_id 
                WHERE u.user_id = ? AND u.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertUser($data)
    {
        // ตรวจสอบว่า username ซ้ำหรือไม่
        if ($this->isUsernameExists($data['username'])) {
            return false;
        }

        $sql = "INSERT INTO $this->dbname (
                firstname, lastname, email, phone, 
                birthdate, address, username, 
                password, user_role_id, image, is_active,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        // จัดการรูปภาพ
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        return $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $data['phone'],
            $data['birthdate'],
            $data['address'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['user_role_id'],
            $image,
            $data['is_active'] ?? 1
        ]);
    }

    public function updateUser($data, $files = null)
    {
            // ตรวจสอบว่ามีผู้ใช้อยู่จริง
            $currentUser = $this->getUserById($data['user_id']);
            if (!$currentUser) {
                return false;
            }

            // ตรวจสอบ username ซ้ำ (เฉพาะกรณีที่เปลี่ยน username)
            if ($data['username'] !== $currentUser['username'] && $this->isUsernameExists($data['username'])) {
                return false;
            }

            $sql = "UPDATE $this->dbname SET 
                firstname = ?,
                lastname = ?,
                email = ?,
                phone = ?,
                birthdate = ?,
                address = ?,
                username = ?,
                user_role_id = ?,
                is_active = ?,
                updated_at = NOW()";

            $params = [
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['phone'],
                $data['birthdate'],
                $data['address'],
                $data['username'],
                $data['user_role_id'],
                $data['is_active']
            ];

            // เพิ่ม password ถ้ามีการส่งมา
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            // จัดการรูปภาพใหม่
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // อัพโหลดรูปใหม่
                $newImage = $this->uploadImage($_FILES['image']);
                if ($newImage) {
                    $sql .= ", image = ?";
                    $params[] = $newImage;

                    // ลบรูปเก่า
                    if (!empty($currentUser['image'])) {
                        $oldImagePath = $this->uploadPath . $currentUser['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $data['user_id'];

            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute($params);
    }

    public function deleteUser($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE user_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE user_id = ?";
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

    public function updateLastLogin($id)
    {
        $sql = "UPDATE $this->dbname SET 
                last_login = NOW()
                WHERE user_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    private function isUsernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM $this->dbname WHERE username = ? AND deleted_at IS NULL";
        $params = [$username];

        if ($excludeId) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    private function uploadImage($file)
    {
        try {
            // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
            if (!file_exists($this->uploadPath)) {
                mkdir($this->uploadPath, 0777, true);
            }

            // ตรวจสอบประเภทไฟล์
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('ประเภทไฟล์ไม่ถูกต้อง');
            }

            // ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
            if ($file['size'] > 2 * 1024 * 1024) {
                throw new Exception('ขนาดไฟล์ต้องไม่เกิน 2MB');
            }

            // สร้างชื่อไฟล์ใหม่
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFilename = uniqid() . '_' . time() . '.' . $extension;
            $uploadPath = $this->uploadPath . $newFilename;

            // อัพโหลดไฟล์
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return $newFilename;
            }

            throw new Exception('ไม่สามารถอัพโหลดไฟล์ได้');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
