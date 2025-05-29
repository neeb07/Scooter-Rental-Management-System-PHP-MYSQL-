<?php
session_start();
include 'db_connection.php';

// Strict admin verification
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    header("Location: app.php?error=admin_access_required");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$rental_id = $_GET['id'];

try {
    $conn->beginTransaction();
    
    // Get rental details (admin can delete any rental)
    $stmt = $conn->prepare("SELECT scooter_id, rental_end FROM rentals WHERE id = :id");
    $stmt->bindParam(':id', $rental_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$rental) {
        throw new Exception("Rental not found");
    }
    
    // If rental is active, make scooter available again
    if (!$rental['rental_end']) {
        $stmt = $conn->prepare("UPDATE scooters SET is_available = 1 WHERE id = :scooter_id");
        $stmt->bindParam(':scooter_id', $rental['scooter_id'], PDO::PARAM_INT);
        $stmt->execute();
    }
    
    // Delete rental record
    $stmt = $conn->prepare("DELETE FROM rentals WHERE id = :id");
    $stmt->bindParam(':id', $rental_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $conn->commit();
    
    header("Location: app.php?message=Rental+deleted+successfully");
    exit();

} catch (Exception $e) {
    $conn->rollBack();
    header("Location: admin_dashboard.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>