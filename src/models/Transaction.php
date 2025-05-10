<?php
class Transaction
{
    private $db;
    private $dbname = 'transaction';
    private $uploadPath = 'assets/uploads/transaction/';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getTransactionsByBookingId($booking_id)
    {
        $sql = "SELECT b.*, t.image as tansaction_image
        FROM $this->dbname t
        LEFT JOIN booking b ON b.booking_id = t.booking_id
        WHERE t.booking_id = ? AND t.deleted_at IS NULL";

        $params = [$booking_id];

        $sql .= " ORDER BY t.created_at DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertTransaction($data)
    {
        $sql = "INSERT INTO $this->dbname (
            booking_id, image, is_active, created_at, updated_at
        ) VALUES (?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);

        // จัดการรูปภาพ
        $image = NULL;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        return $stmt->execute([
            $data['booking_id'],
            $image,
            $data['is_active'] ?? '1'
        ]);
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
