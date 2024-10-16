<?php
// models/Customer.php

class Customer{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllCustomer() {
        $sql = "SELECT * FROM customer";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById($id) {
        $sql = "SELECT * FROM customer WHERE customer_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertCustomer($data) {
        $sql = "INSERT INTO customer (name, surname, email, phone, age) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$data['name'], $data['surname'], $data['email'], $data['phone'], $data['age']]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateCustomer($data) {
        $sql = "UPDATE customer SET name = ?, surname = ?, email = ?, phone = ?, age = ? WHERE customer_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$data['name'], $data['surname'], $data['email'], $data['phone'], $data['age'],$data['customer_id']]);
    }

    public function deleteCustomer($id) {
        $sql = "DELETE FROM customer WHERE customer_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}
    ?>