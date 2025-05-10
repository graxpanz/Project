<?php
class Service
{
    private $db;
    private $dbname = 'service';
    private $uploadPath = 'assets/uploads/service/';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllServices()
    {
        $sql = "SELECT s.*, st.name as service_type_name
        FROM $this->dbname s
        LEFT JOIN service_type st ON s.service_type_id = st.service_type_id  
        WHERE s.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceById($id)
    {
        $sql = "SELECT * FROM $this->dbname WHERE service_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertService($data)
    {
        $sql = "INSERT INTO $this->dbname (
            service_type_id, image, name, description, price, time, is_active, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        // จัดการรูปภาพ
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        return $stmt->execute([
            $data['service_type_id'],
            $image,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['time'],
            $data['is_active'] ?? 1
        ]);
    }

    public function updateService($data)
    {
        $currentService = $this->getServiceById($data['service_id']);
        if (!$currentService) {
            return false;
        }

        $sql = "UPDATE $this->dbname SET 
            service_type_id = ?,
            name = ?,
            description = ?,
            price = ?,
            time = ?,
            is_active = ?,
            updated_at = NOW()";

        $params = [
            $data['service_type_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['time'],
            $data['is_active']
        ];

        // จัดการรูปภาพใหม่
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // อัพโหลดรูปใหม่
            $newImage = $this->uploadImage($_FILES['image']);
            if ($newImage) {
                $sql .= ", image = ?";
                $params[] = $newImage;

                // ลบรูปเก่า
                if (!empty($currentService['image'])) {
                    $oldImagePath = $this->uploadPath . $currentService['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }

        $sql .= " WHERE service_id = ?";
        $params[] = $data['service_id'];

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteService($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE service_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE service_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
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

            // ตรวจสอบขนาดไฟล์ (ไม่เกิน 10MB)
            if ($file['size'] > 10 * 1024 * 1024) {
                throw new Exception('ขนาดไฟล์ต้องไม่เกิน 10MB');
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

    // นับจำนวนบริการที่มีสถานะเปิดใช้งาน
    public function countActiveServices()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM service 
                WHERE is_active = '1' AND deleted_at IS NULL";
            $stmt = $this->db->getConnection()->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Error counting services: " . $e->getMessage());
            return 0;
        }
    }
}
