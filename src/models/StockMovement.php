<?php
class StockMovement
{
    private $db;
    private $dbname = 'stock_movement';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMovements()
    {
        $sql = "SELECT sm.*, s.name as supply_name, s.unit 
                FROM $this->dbname sm
                LEFT JOIN supplies s ON sm.supply_id = s.supply_id
                WHERE sm.deleted_at IS NULL
                ORDER BY sm.movement_date DESC, sm.created_at DESC";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovementById($id)
    {
        $sql = "SELECT sm.*, s.name as supply_name, s.unit 
                FROM $this->dbname sm
                LEFT JOIN supplies s ON sm.supply_id = s.supply_id
                WHERE sm.movement_id = ? AND sm.deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMovementsBySupplyId($supply_id)
    {
        $sql = "SELECT sm.*, s.name as supply_name, s.unit 
                FROM $this->dbname sm
                LEFT JOIN supplies s ON sm.supply_id = s.supply_id
                WHERE sm.supply_id = ? AND sm.deleted_at IS NULL
                ORDER BY sm.movement_date DESC, sm.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$supply_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovementsByDateRange($start_date, $end_date)
    {
        $sql = "SELECT sm.*, s.name as supply_name, s.unit 
                FROM $this->dbname sm
                LEFT JOIN supplies s ON sm.supply_id = s.supply_id
                WHERE sm.movement_date BETWEEN ? AND ? AND sm.deleted_at IS NULL
                ORDER BY sm.movement_date DESC, sm.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCurrentStock($supply_id)
    {
        $sql = "SELECT COALESCE(SUM(CASE WHEN movement_type = 'in' THEN quantity ELSE -quantity END), 0) as current_stock
                FROM $this->dbname
                WHERE supply_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$supply_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['current_stock'] : 0;
    }

    public function insertMovement($data)
    {
        $sql = "INSERT INTO $this->dbname (
            supply_id, quantity, movement_type, reference, notes, movement_date, is_active, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->db->getConnection()->prepare($sql);
        
        return $stmt->execute([
            $data['supply_id'],
            $data['quantity'],
            $data['movement_type'],
            $data['reference'] ?? null,
            $data['notes'] ?? null,
            $data['movement_date'],
            $data['is_active'] ?? '1'
        ]);
    }

    public function updateMovement($data)
    {
        $currentMovement = $this->getMovementById($data['movement_id']);
        if (!$currentMovement) {
            return false;
        }

        $sql = "UPDATE $this->dbname SET 
            supply_id = ?,
            quantity = ?,
            movement_type = ?,
            reference = ?,
            notes = ?,
            movement_date = ?,
            is_active = ?,
            updated_at = NOW()
            WHERE movement_id = ?";

        $stmt = $this->db->getConnection()->prepare($sql);
        
        return $stmt->execute([
            $data['supply_id'],
            $data['quantity'],
            $data['movement_type'],
            $data['reference'] ?? null,
            $data['notes'] ?? null,
            $data['movement_date'],
            $data['is_active'],
            $data['movement_id']
        ]);
    }

    public function deleteMovement($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE movement_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE movement_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
}