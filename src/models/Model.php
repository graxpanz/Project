<?php
// models/Employee.php

class Employee {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllEmployees() {
        $sql = "SELECT employees.emp_id, employees.fname, employees.lname, employees.tel, employees.email, employees.bdate, employees.age, employees.address, position_type.position_name 
                FROM employees 
                JOIN employees_position ON employees.emp_id = employees_position.emp_id
                JOIN position_type ON employees_position.position_id = position_type.position_id";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeeById($id) {
        $sql = "SELECT * FROM employees WHERE emp_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertEmployee($data) {
        $sql = "INSERT INTO employees (fname, lname, bdate, age, tel, email, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$data['fname'], $data['lname'], $data['bdate'], $data['age'], $data['tel'], $data['email'], $data['address']]);
        return $this->db->getConnection()->lastInsertId();
    }

    public function updateEmployee($data) {
        $sql = "UPDATE employees SET fname = ?, lname = ?, tel = ?, email = ?, bdate = ?, age = ?, address = ? WHERE emp_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$data['fname'], $data['lname'], $data['tel'], $data['email'], $data['bdate'], $data['age'], $data['address'], $data['emp_id']]);
    }

    public function deleteEmployee($id) {
        $sql = "DELETE FROM employees WHERE emp_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getPositions() {
        $sql = "SELECT position_id, position_name FROM position_type";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmployeePositions($empId) {
        $sql = "SELECT position_id FROM employees_position WHERE emp_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$empId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function updateEmployeePositions($empId, $positions) {
        $this->db->getConnection()->beginTransaction();

        try {
            $sql = "DELETE FROM employees_position WHERE emp_id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$empId]);

            $sql = "INSERT INTO employees_position (emp_id, position_id) VALUES (?, ?)";
            $stmt = $this->db->getConnection()->prepare($sql);
            foreach ($positions as $positionId) {
                $stmt->execute([$empId, $positionId]);
            }

            $this->db->getConnection()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->getConnection()->rollBack();
            return false;
        }
    }
}