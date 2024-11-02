<?php
class Finance {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllFinances() {
        $sql = "SELECT * FROM finance ORDER BY date DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFinanceById($id) {
        $sql = "SELECT * FROM finance WHERE finance_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertFinance($data) {
        $sql = "INSERT INTO finance (date, list, income, expense, total, note) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        // คำนวณยอดคงเหลือ
        $total = floatval($data['income']) - floatval($data['expense']);
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['date'],
            $data['list'],
            $data['income'],
            $data['expense'],
            $total,
            $data['note']
        ]);
    }

    public function updateFinance($data) {
        $sql = "UPDATE finance SET 
                date = ?, 
                list = ?, 
                income = ?, 
                expense = ?, 
                total = ?,
                note = ? 
                WHERE finance_id = ?";
        
        // คำนวณยอดคงเหลือ
        $total = floatval($data['income']) - floatval($data['expense']);
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['date'],
            $data['list'],
            $data['income'],
            $data['expense'],
            $total,
            $data['note'],
            $data['finance_id']
        ]);
    }

    public function deleteFinance($id) {
        $sql = "DELETE FROM finance WHERE finance_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getSummary() {
        $sql = "SELECT 
                SUM(income) as total_income,
                SUM(expense) as total_expense,
                SUM(income - expense) as net_total
                FROM finance";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}