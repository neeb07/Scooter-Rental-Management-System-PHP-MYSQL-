<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scooter_id = $_POST['scooter_id'];
    $customer_name = $_POST['customer_name'];
    $rental_start = $_POST['rental_start'];
    
    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Insert rental record
        $stmt = $conn->prepare("INSERT INTO rentals (scooter_id, customer_name, rental_start) 
                                VALUES (:scooter_id, :customer_name, :rental_start)");
        
        $stmt->bindParam(':scooter_id', $scooter_id);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':rental_start', $rental_start);
        
        $stmt->execute();
        
        // Update scooter availability
        $stmt = $conn->prepare("UPDATE scooters SET is_available = 0 WHERE id = :scooter_id");
        $stmt->bindParam(':scooter_id', $scooter_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        header("Location: index.php?message=Rental created successfully");
        exit();
    } catch(PDOException $e) {
        // Rollback transaction if error occurs
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>