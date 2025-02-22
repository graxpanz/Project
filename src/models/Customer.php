<?php
// models/Customer.php

class Customer {
    private $db;
    private $tablename = 'customer';
    private $soft_delete = true;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCustomer() {
        $sql = "SELECT * FROM {$this->tablename} WHERE deleted_at IS NULL";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id) {
        $sql = "SELECT * FROM {$this->tablename} WHERE customer_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCustomer($data) {
        $sql = "INSERT INTO {$this->tablename} (
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

    public function updateCustomer($data) {
        $sql = "UPDATE {$this->tablename} SET 
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

    public function deleteCustomer($id) {
        if ($this->soft_delete) {
            $sql = "UPDATE {$this->tablename} SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE customer_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM {$this->tablename} WHERE customer_id = ?";
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>