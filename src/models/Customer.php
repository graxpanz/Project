<?php
// models/Customer.php

class Customer
{
    private $db;
    private $dbname = 'customer';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCustomers()
    {
        $sql = "SELECT * FROM $this->dbname WHERE deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id)
    {
        $sql = "SELECT * FROM $this->dbname WHERE customer_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCustomer($data)
    {
        $sql = "INSERT INTO $this->dbname (
            email,
            password,
            firstname,
            lastname,
            phone,
            birthdate,
            address
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            $data['email'],
            $data['password'],
            $data['firstname'],
            $data['lastname'],
            $data['phone'],
            $data['birthdate'],
            $data['address']
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateCustomer($data)
    {
        $sql = "UPDATE $this->dbname SET 
            email = ?,
            firstname = ?,
            lastname = ?,
            phone = ?,
            birthdate= ?,
            address = ?,
            is_active = ?
            WHERE customer_id = ? AND deleted_at IS NULL";

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['email'],
            $data['firstname'],
            $data['lastname'],
            $data['phone'],
            $data['birthdate'],
            $data['address'],
            $data['is_active'],
            $data['customer_id']
        ]);
    }

    public function deleteCustomer($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE customer_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE customer_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }


    public function isEmailExists($email, $excludeCustomerId = null)
    {
        if ($excludeCustomerId) {
            $sql = "SELECT COUNT(*) FROM $this->dbname 
                    WHERE email = ? AND customer_id != ? AND deleted_at IS NULL";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$email, $excludeCustomerId]);
        } else {
            $sql = "SELECT COUNT(*) FROM $this->dbname 
                    WHERE email = ? AND deleted_at IS NULL";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$email]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function getCustomerByEmail($email)
    {
        $sql = "SELECT * FROM $this->dbname WHERE email = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSession($customerId, $token, $ipAddress, $userAgent)
    {
        $sql = "INSERT INTO customer_session (
            session_id,
            customer_id,
            token,
            ip_address,
            user_agent,
            expired_at
        ) VALUES (?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))";

        $sessionId = bin2hex(random_bytes(16));
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $sessionId,
            $customerId,
            $token,
            $ipAddress,
            $userAgent
        ]);
    }

    public function validateSession($token)
    {
        $sql = "SELECT cs.*, c.* 
                FROM customer_session cs 
                JOIN $this->dbname c ON cs.customer_id = c.customer_id 
                WHERE cs.token = ? 
                AND cs.expired_at > NOW() 
                AND c.deleted_at IS NULL 
                AND c.is_active = '1'";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteSession($token)
    {
        $sql = "DELETE FROM customer_session WHERE token = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$token]);
    }

    // นับจำนวนลูกค้าที่มีสถานะเปิดใช้งาน
    public function countActiveCustomers()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM customer 
                WHERE is_active = '1' AND deleted_at IS NULL";
            $stmt = $this->db->getConnection()->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['total'] : 0;
        } catch (PDOException $e) {
            error_log("Error counting customers: " . $e->getMessage());
            return 0;
        }
    }

    // สถิติลูกค้าใหม่รายเดือน
    public function getNewCustomersMonthly($year)
    {
        try {
            $sql = "SELECT MONTH(created_at) as month, COUNT(*) as count
                FROM customer
                WHERE YEAR(created_at) = ?
                AND deleted_at IS NULL
                GROUP BY MONTH(created_at)
                ORDER BY MONTH(created_at)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$year]);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $monthly_data = array_fill(1, 12, 0); // สร้างอาร์เรย์สำหรับ 12 เดือน

            foreach ($result as $row) {
                $monthly_data[$row['month']] = $row['count'];
            }

            return $monthly_data;
        } catch (PDOException $e) {
            error_log("Error getting monthly customer stats: " . $e->getMessage());
            return array_fill(1, 12, 0);
        }
    }
}
