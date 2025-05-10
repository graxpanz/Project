<?php
class Finance
{
    private $db;
    private $dbname = 'finance';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllFinances()
    {
        $sql = "SELECT * FROM $this->dbname WHERE deleted_at IS NULL ORDER BY transaction_date DESC, created_at DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFinanceById($id)
    {
        $sql = "SELECT * FROM $this->dbname WHERE finance_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFinancesByDateRange($start_date, $end_date)
    {
        $sql = "SELECT * FROM $this->dbname 
                WHERE transaction_date BETWEEN ? AND ? 
                AND deleted_at IS NULL 
                ORDER BY transaction_date DESC, created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummaryByDateRange($start_date, $end_date)
    {
        $sql = "SELECT 
                    SUM(income) as total_income, 
                    SUM(outcome) as total_outcome,
                    (SUM(income) - SUM(outcome)) as net_balance
                FROM $this->dbname 
                WHERE transaction_date BETWEEN ? AND ? 
                AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMonthlySummary($year)
    {
        $sql = "SELECT 
                    MONTH(transaction_date) as month,
                    SUM(income) as total_income, 
                    SUM(outcome) as total_outcome,
                    (SUM(income) - SUM(outcome)) as net_balance
                FROM $this->dbname 
                WHERE YEAR(transaction_date) = ? 
                AND deleted_at IS NULL
                GROUP BY MONTH(transaction_date)
                ORDER BY MONTH(transaction_date)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertFinance($data)
    {
        try {
            $sql = "INSERT INTO $this->dbname (
                description, income, outcome, transaction_date, is_active, created_at, updated_at
            ) VALUES (
                :description, :income, :outcome, :transaction_date, :is_active, NOW(), NOW()
            )";

            $params = [
                ':description' => $data['description'],
                ':income' => $data['income'] ?? 0,
                ':outcome' => $data['outcome'] ?? 0,
                ':transaction_date' => $data['transaction_date'],
                ':is_active' => $data['is_active'] ?? '1'
            ];

            $stmt = $this->db->getConnection()->prepare($sql);
            $result = $stmt->execute($params);

            if ($result) {
                return $this->db->getConnection()->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error inserting finance: " . $e->getMessage());
            return false;
        }
    }

    public function updateFinance($data)
    {
        $currentFinance = $this->getFinanceById($data['finance_id']);
        if (!$currentFinance) {
            return false;
        }

        try {
            $sql = "UPDATE $this->dbname SET 
                description = :description,
                income = :income,
                outcome = :outcome,
                transaction_date = :transaction_date,
                is_active = :is_active,
                updated_at = NOW()
                WHERE finance_id = :finance_id";

            $params = [
                ':description' => $data['description'],
                ':income' => $data['income'] ?? 0,
                ':outcome' => $data['outcome'] ?? 0,
                ':transaction_date' => $data['transaction_date'],
                ':is_active' => $data['is_active'],
                ':finance_id' => $data['finance_id']
            ];

            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating finance: " . $e->getMessage());
            return false;
        }
    }

    public function deleteFinance($id)
    {
        try {
            if ($this->soft_delete) {
                $sql = "UPDATE $this->dbname SET 
                    deleted_at = NOW(),
                    is_active = '0' 
                    WHERE finance_id = ? AND deleted_at IS NULL";
            } else {
                $sql = "DELETE FROM $this->dbname WHERE finance_id = ?";
            }

            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting finance: " . $e->getMessage());
            return false;
        }
    }
}