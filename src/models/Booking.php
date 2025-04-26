<?php
class Booking
{
    private $db;
    private $dbname = 'booking';
    private $soft_delete = true;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBookings()
    {
        $sql = "SELECT 
                    b.*,
                    c.email as customer_email,
                    c.firstname as customer_firstname, 
                    c.lastname as customer_lastname,
                    c.phone as customer_phone,
                    c.birthdate as customer_birthdate,
                    c.address as customer_address,
                    s.image as service_image,
                    s.name as service_name,
                    s.price as service_price,
                    s.time as service_time,
                    st.name as service_type_name,
                    u.image as user_image,
                    u.firstname as user_firstname,
                    u.lastname as user_lastname,
                    u.email as user_email,
                    u.phone as user_phone,
                    u.birthdate as user_birthdate,
                    ur.name as user_role_name,
                    p.image as promotion_image,
                    p.name as promotion_name,
                    p.discount as promotion_discount,
                    p.code as promotion_code,
                    p.start_datetime as promotion_start_datetime,
                    p.end_datetime as promotion_end_datetime
                FROM $this->dbname b
                LEFT JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN service s ON b.service_id = s.service_id
                LEFT JOIN service_type st ON s.service_type_id = st.service_type_id
                LEFT JOIN user u ON b.user_id = u.user_id
                LEFT JOIN user_role ur ON u.user_role_id = ur.user_role_id
                LEFT JOIN promotion p ON b.promotion_id = p.promotion_id
                WHERE b.deleted_at IS NULL
                ORDER BY b.appointment_datetime ASC";
        
        $stmt = $this->db->getConnection()->query($sql);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->formattedBookings($bookings);
    }

    public function getBookingById($id)
    {
        $sql = "SELECT * FROM $this->dbname WHERE booking_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookingsByCustomerId($customerId, $status = null)
    {
        try {
            $sql = "SELECT 
                        b.*,
                        c.email as customer_email,
                        c.firstname as customer_firstname, 
                        c.lastname as customer_lastname,
                        c.phone as customer_phone,
                        c.birthdate as customer_birthdate,
                        c.address as customer_address,
                        s.image as service_image,
                        s.name as service_name,
                        s.price as service_price,
                        s.time as service_time,
                        st.name as service_type_name,
                        u.image as user_image,
                        u.firstname as user_firstname,
                        u.lastname as user_lastname,
                        u.email as user_email,
                        u.phone as user_phone,
                        u.birthdate as user_birthdate,
                        ur.name as user_role_name,
                        p.image as promotion_image,
                        p.name as promotion_name,
                        p.discount as promotion_discount,
                        p.code as promotion_code,
                        p.start_datetime as promotion_start_datetime,
                        p.end_datetime as promotion_end_datetime
                    FROM $this->dbname b
                    LEFT JOIN customer c ON b.customer_id = c.customer_id
                    LEFT JOIN service s ON b.service_id = s.service_id
                    LEFT JOIN service_type st ON s.service_type_id = st.service_type_id
                    LEFT JOIN user u ON b.user_id = u.user_id
                    LEFT JOIN user_role ur ON u.user_role_id = ur.user_role_id
                    LEFT JOIN promotion p ON b.promotion_id = p.promotion_id
                    WHERE b.customer_id = :customer_id AND b.deleted_at IS NULL";
                    
            if ($status) {
                $sql .= " AND b.status = :status";
            }
            
            $sql .= " ORDER BY b.created_at ASC";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            
            if ($status) {
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $this->formattedBookings($bookings);
        } catch (PDOException $e) {
            error_log("Error getting bookings by customer: " . $e->getMessage());
            return [];
        }
    }

    public function insertBooking($data)
    {
        try {
            // Combine appointment date and time
            $appointmentDatetime = $data['appointment_date'] . ' ' . $data['appointment_time'] . ':00';
            
            $sql = "INSERT INTO $this->dbname (
                customer_id, service_id, user_id, promotion_id, appointment_datetime,
                price, discount, deposit_price, total_price, note, status, is_active,
                created_at, updated_at
            ) VALUES (
                :customer_id, :service_id, :user_id, :promotion_id, :appointment_datetime,
                :price, :discount, :deposit_price, :total_price, :note, :status, '1',
                NOW(), NOW()
            )";

            $stmt = $this->db->getConnection()->prepare($sql);
            
            // Handle null for optional fields
            $userId = !empty($data['user_id']) ? $data['user_id'] : null;
            $promotionId = !empty($data['promotion_id']) ? $data['promotion_id'] : null;
            
            $params = [
                ':customer_id' => $data['customer_id'],
                ':service_id' => $data['service_id'],
                ':user_id' => $userId,
                ':promotion_id' => $promotionId,
                ':appointment_datetime' => $appointmentDatetime,
                ':price' => $data['price'],
                ':discount' => $data['discount'],
                ':deposit_price' => $data['deposit_price'],
                ':total_price' => $data['total_price'],
                ':note' => $data['note'] ?? null,
                ':status' => $data['status']
            ];

            $result = $stmt->execute($params);
            
            if ($result) {
                // Return the last inserted ID
                return $this->db->getConnection()->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            // Log error
            error_log("Error inserting booking: " . $e->getMessage());
            return false;
        }
    }

    public function updateBooking($data)
    {
        $currentBooking = $this->getBookingById($data['booking_id']);
        if (!$currentBooking) {
            return false;
        }

        try {
            // Combine appointment date and time
            $appointmentDatetime = $data['appointment_date'] . ' ' . $data['appointment_time'] . ':00';
            
            $sql = "UPDATE $this->dbname SET 
                customer_id = :customer_id,
                service_id = :service_id,
                user_id = :user_id,
                promotion_id = :promotion_id,
                appointment_datetime = :appointment_datetime,
                price = :price,
                discount = :discount,
                deposit_price = :deposit_price,
                total_price = :total_price,
                note = :note,
                status = :status,
                is_active = :is_active,
                updated_at = NOW() 
                WHERE booking_id = :booking_id";

            // Handle null for optional fields
            $userId = !empty($data['user_id']) ? $data['user_id'] : null;
            $promotionId = !empty($data['promotion_id']) ? $data['promotion_id'] : null;
            
            $params = [
                ':customer_id' => $data['customer_id'],
                ':service_id' => $data['service_id'],
                ':user_id' => $userId,
                ':promotion_id' => $promotionId,
                ':appointment_datetime' => $appointmentDatetime,
                ':price' => $data['price'],
                ':discount' => $data['discount'],
                ':deposit_price' => $data['deposit_price'],
                ':total_price' => $data['total_price'],
                ':note' => $data['note'] ?? null,
                ':status' => $data['status'],
                ':is_active' => $data['is_active'] ?? '1',
                ':booking_id' => $data['booking_id']
            ];

            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            // Log error
            error_log("Error updating booking: " . $e->getMessage());
            return false;
        }
    }

    public function deleteBooking($id)
    {
        if ($this->soft_delete) {
            $sql = "UPDATE $this->dbname SET 
                deleted_at = NOW(),
                is_active = '0' 
                WHERE booking_id = ? AND deleted_at IS NULL";
        } else {
            $sql = "DELETE FROM $this->dbname WHERE booking_id = ?";
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function updateBookingStatus($booking_id, $status)
    {
        try {
            $sql = "UPDATE $this->dbname SET 
                    status = :status,
                    updated_at = NOW() 
                    WHERE booking_id = :booking_id";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating booking status: " . $e->getMessage());
            return false;
        }
    }

    private function formattedBookings($bookings) {
        $formattedBookings = [];
        foreach ($bookings as $booking) {
            $formattedBookings[] = [
                'booking_id' => $booking['booking_id'],
                'customer_id' => $booking['customer_id'],
                'service_id' => $booking['service_id'],
                'user_id' => $booking['user_id'],
                'promotion_id' => $booking['promotion_id'],
                'appointment_datetime' => $booking['appointment_datetime'],
                'price' => $booking['price'],
                'discount' => $booking['discount'],
                'deposit_price' => $booking['deposit_price'],
                'total_price' => $booking['total_price'],
                'note' => $booking['note'],
                'status' => $booking['status'],
                'is_active' => $booking['is_active'],
                'created_at' => $booking['created_at'],
                'updated_at' => $booking['updated_at'],
                'customer' => [
                    'email' => $booking['customer_email'],
                    'firstname' => $booking['customer_firstname'],
                    'lastname' => $booking['customer_lastname'],
                    'phone' => $booking['customer_phone'],
                    'birthdate' => $booking['customer_birthdate'],
                    'address' => $booking['customer_address']
                ],
                'service' => [
                    'image' => $booking['service_image'],
                    'name' => $booking['service_name'],
                    'price' => $booking['service_price'],
                    'time' => $booking['service_time'],
                    'type' => [
                        'name' => $booking['service_type_name']
                    ]
                ],
                'user' => $booking['user_id'] ? [
                    'image' => $booking['user_image'],
                    'firstname' => $booking['user_firstname'],
                    'lastname' => $booking['user_lastname'],
                    'email' => $booking['user_email'],
                    'phone' => $booking['user_phone'],
                    'birthdate' => $booking['user_birthdate'],
                    'role' => [
                        'name' => $booking['user_role_name']
                    ]
                ] : null,
                'promotion' => $booking['promotion_id'] ? [
                    'image' => $booking['promotion_image'],
                    'name' => $booking['promotion_name'],
                    'discount' => $booking['promotion_discount'],
                    'code' => $booking['promotion_code'],
                    'start_datetime' => $booking['promotion_start_datetime'],
                    'end_datetime' => $booking['promotion_end_datetime']
                ] : null
            ];
        }
        
        return $formattedBookings;
    }
}
