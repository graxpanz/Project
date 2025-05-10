<?php
class Supply
{
    private $db;
    private $dbname = 'supplies';
    private $uploadPath = 'assets/uploads/supplies/';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSupplies()
    {
        $sql = "SELECT s.*, 
                    (SELECT COALESCE(SUM(CASE WHEN sm.movement_type = 'in' THEN sm.quantity ELSE -sm.quantity END), 0) 
                     FROM stock_movement sm 
                     WHERE sm.supply_id = s.supply_id AND sm.deleted_at IS NULL) as current_stock 
                FROM $this->dbname s 
                WHERE s.deleted_at IS NULL
                ORDER BY s.name ASC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplyById($id)
    {
        $sql = "SELECT s.*, 
                    (SELECT COALESCE(SUM(CASE WHEN sm.movement_type = 'in' THEN sm.quantity ELSE -sm.quantity END), 0) 
                     FROM stock_movement sm 
                     WHERE sm.supply_id = s.supply_id AND sm.deleted_at IS NULL) as current_stock 
                FROM $this->dbname s 
                WHERE s.supply_id = ? AND s.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLowStockSupplies()
    {
        $sql = "SELECT s.*, 
                    (SELECT COALESCE(SUM(CASE WHEN sm.movement_type = 'in' THEN sm.quantity ELSE -sm.quantity END), 0) 
                     FROM stock_movement sm 
                     WHERE sm.supply_id = s.supply_id AND sm.deleted_at IS NULL) as current_stock 
                FROM $this->dbname s 
                WHERE s.deleted_at IS NULL
                HAVING current_stock <= s.min_quantity
                ORDER BY s.name ASC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertSupply($data)
    {
        $sql = "INSERT INTO $this->dbname (
            name, image, description, unit, min_quantity, is_active, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        // จัดการรูปภาพ
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        return $stmt->execute([
            $data['name'],
            $image,
            $data['description'],
            $data['unit'],
            $data['min_quantity'],
            $data['is_active'] ?? '1'
        ]);
    }

    public function updateSupply($data)
    {
        $currentSupply = $this->getSupplyById($data['supply_id']);
        if (!$currentSupply) {
            return false;
        }

        $sql = "UPDATE $this->dbname SET 
            name = ?,
            description = ?,
            unit = ?,
            min_quantity = ?,
            is_active = ?,
            updated_at = NOW()";

        $params = [
            $data['name'],
            $data['description'],
            $data['unit'],
            $data['min_quantity'],
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
                if (!empty($currentSupply['image'])) {
                    $oldImagePath = $this->uploadPath . $currentSupply['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }

        $sql .= " WHERE supply_id = ?";
        $params[] = $data['supply_id'];

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteSupply($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE supply_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE supply_id = ?";
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
}