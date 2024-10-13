<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findByUsername($username) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}