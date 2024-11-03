<?php
class User
{
    private $db;
    private $uploadPath = 'uploads/managers/'; // กำหนด path ที่จะเก็บรูปภาพ

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findByUsername($username)
    {
        $sql = "SELECT u.*, r.permission as permission
                FROM users u 
                LEFT JOIN role r ON u.role_id = r.role_id 
                WHERE u.username = ? AND u.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllManagers()
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN role r ON u.role_id = r.role_id 
                WHERE u.deleted_at IS NULL 
                ORDER BY u.created_at DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getManagerById($id)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN role r ON u.role_id = r.role_id 
                WHERE u.user_id = ? AND u.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertManager($data)
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // ตรวจสอบว่า username ซ้ำหรือไม่
            if ($this->isUsernameExists($data['username'])) {
                // throw new Exception("Username นี้ถูกใช้งานแล้ว");
                return false;
            }

            $sql = "INSERT INTO users (
                firstname, lastname, email, phone, 
                birthdate, age, address, username, 
                password, role_id, image, is_active,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

            $stmt = $this->db->getConnection()->prepare($sql);

            // จัดการรูปภาพ
            $image = 'default.png';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $this->uploadImage($_FILES['image']);
            }

            $success = $stmt->execute([
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['phone'],
                $data['birthdate'],
                $data['age'],
                $data['address'],
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT), // ใช้ password_hash แทน md5
                $data['role_id'],
                $image,
                $data['is_active'] ?? 1
            ]);

            if ($success) {
                $this->db->getConnection()->commit();
                return true;
            }

            throw new Exception("ไม่สามารถบันทึกข้อมูลได้");
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }

    public function updateManager($data, $files = null)
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // ตรวจสอบว่ามีผู้ใช้อยู่จริง
            $currentUser = $this->getManagerById($data['user_id']);
            if (!$currentUser) {
                return false;
            }

            // ตรวจสอบ username ซ้ำ (เฉพาะกรณีที่เปลี่ยน username)
            if ($data['username'] !== $currentUser['username'] && $this->isUsernameExists($data['username'])) {
                return false;
            }

            $sql = "UPDATE users SET 
                firstname = ?,
                lastname = ?,
                email = ?,
                phone = ?,
                birthdate = ?,
                age = ?,
                address = ?,
                username = ?,
                role_id = ?,
                is_active = ?,
                updated_at = NOW()";

            $params = [
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['phone'],
                $data['birthdate'],
                $data['age'],
                $data['address'],
                $data['username'],
                $data['role_id'],
                $data['is_active']
            ];

            // เพิ่ม password ถ้ามีการส่งมา
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            // จัดการรูปภาพใหม่
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                try {
                    // อัพโหลดรูปใหม่
                    $newImage = $this->uploadImage($_FILES['image']);
                    if ($newImage) {
                        $sql .= ", image = ?";
                        $params[] = $newImage;

                        // ลบรูปเก่าถ้าไม่ใช่รูป default
                        if (!empty($currentUser['image']) && $currentUser['image'] !== 'default.png') {
                            $oldImagePath = $this->uploadPath . $currentUser['image'];
                            if (file_exists($oldImagePath)) {
                                unlink($oldImagePath);
                            }
                        }
                    }
                } catch (Exception $e) {
                    // หากมีข้อผิดพลาดในการอัพโหลดรูป ให้ทำงานต่อโดยไม่อัพเดทรูปภาพ
                    error_log("Error uploading image: " . $e->getMessage());
                }
            }

            $sql .= " WHERE user_id = ?";
            $params[] = $data['user_id'];

            $stmt = $this->db->getConnection()->prepare($sql);
            $success = $stmt->execute($params);

            if ($success) {
                $this->db->getConnection()->commit();
                return true;
            }

            $this->db->getConnection()->rollBack();
            return false;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            error_log("Error updating manager: " . $e->getMessage());
            return false;
        }
    }

    public function deleteManager($id)
    {
        try {
            $this->db->getConnection()->beginTransaction();

            // ใช้ soft delete แทนการลบจริง
            $sql = "UPDATE users SET 
                    deleted_at = NOW(),
                    is_active = 0 
                    WHERE user_id = ? AND deleted_at IS NULL";

            $stmt = $this->db->getConnection()->prepare($sql);
            $success = $stmt->execute([$id]);

            if ($success) {
                $this->db->getConnection()->commit();
                return true;
            }

            throw new Exception("ไม่สามารถลบข้อมูลได้");
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            throw $e;
        }
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET 
                is_active = ?,
                updated_at = NOW() 
                WHERE user_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    private function isUsernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ? AND deleted_at IS NULL";
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
