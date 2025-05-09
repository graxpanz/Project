<?php
class Estimate
{
    private $db;
    private $dbname = 'estimate';
    private $uploadPath = 'assets/uploads/estimate/';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllEstimates()
    {
        $sql = "SELECT e.*,CONCAT(c.firstname, ' ', c.lastname) as customer_name, c.phone as customer_phone
        FROM $this->dbname e
        LEFT JOIN customer c ON e.customer_id = c.customer_id
        WHERE e.deleted_at IS NULL
        ORDER BY e.created_at DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstimateById($id)
    {
        $sql = "SELECT e.*,CONCAT(c.firstname, ' ', c.lastname) as customer_name, c.phone as customer_phone
        FROM $this->dbname e
        LEFT JOIN customer c ON e.customer_id = c.customer_id
        WHERE e.estimate_id = ? AND e.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEstimatesByCustomerId($customer_id, $status = null)
    {
        $sql = "SELECT e.*, CONCAT(c.firstname, ' ', c.lastname) as customer_name, c.phone as customer_phone
        FROM $this->dbname e
        LEFT JOIN customer c ON e.customer_id = c.customer_id
        WHERE e.customer_id = ? AND e.deleted_at IS NULL";
        
        $params = [$customer_id];
        
        if ($status !== null) {
            $sql .= " AND e.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY e.created_at DESC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertEstimate($data)
    {
        $sql = "INSERT INTO $this->dbname (
            customer_id, image, description, response, status, is_active, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        // จัดการรูปภาพ
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        return $stmt->execute([
            $data['customer_id'],
            $image,
            $data['description'],
            $data['response'] ?? NULL,
            $data['status'] ?? 'pending',
            $data['is_active'] ?? '1'
        ]);
    }

    public function updateEstimate($data)
    {
        $currentEstimate = $this->getEstimateById($data['estimate_id']);
        if (!$currentEstimate) {
            return false;
        }

        $sql = "UPDATE $this->dbname SET 
            customer_id = ?,
            description = ?,
            response = ?,
            status = ?,
            is_active = ?,
            updated_at = NOW()";

        $params = [
            $data['customer_id'],
            $data['description'],
            $data['response'],
            $data['status'],
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
                if (!empty($currentEstimate['image'])) {
                    $oldImagePath = $this->uploadPath . $currentEstimate['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }

        $sql .= " WHERE estimate_id = ?";
        $params[] = $data['estimate_id'];

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateEstimateResponse($data)
    {
        $sql = "UPDATE $this->dbname SET 
            response = ?,
            status = 'responsed',
            updated_at = NOW()
            WHERE estimate_id = ?";

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['response'],
            $data['estimate_id']
        ]);
    }

    public function deleteEstimate($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE estimate_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE estimate_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function uploadImage($file)
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

            // throw new Exception('ไม่สามารถอัพโหลดไฟล์ได้');
        } catch (Exception $e) {
            throw $e;
        }
    }
}