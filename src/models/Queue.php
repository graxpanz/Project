<?php
class Queue {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllQueues() {
        $sql = "
        SELECT 
            q.queue_id,
            c.name AS name,
            c.surname AS surname,
            c.phone AS phone,
            st.service_type_name,
            s.service_name,
            e.fname,
            q.queue_date,
            q.queue_time,
            q.status
        FROM queue q
        JOIN customer c ON q.customer_id = c.customer_id
        JOIN service s ON q.service_id = s.service_id
        JOIN employees e ON q.emp_id = e.emp_id
        JOIN service_type st ON q.service_type_id = st.service_type_id
        ";

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountByStatus($status) {
        $sql = "SELECT COUNT(*) as count FROM queue WHERE status = :status";
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getQueueById($id) {
        $sql = "
        SELECT 
            q.queue_id,
            c.name AS name,
            c.surname AS surname,
            c.phone AS phone,
            st.service_type_name,
            s.service_name,
            e.fname AS employee_name,
            q.queue_date,
            q.queue_time,
            q.status
        FROM queue q
        JOIN customer c ON q.customer_id = c.customer_id
        JOIN service s ON q.service_id = s.service_id
        JOIN employees e ON q.emp_id = e.emp_id
        JOIN service_type st ON q.service_type_id = st.service_type_id
        WHERE q.queue_id = :id
        ";

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}