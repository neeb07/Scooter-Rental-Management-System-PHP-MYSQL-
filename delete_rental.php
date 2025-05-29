<?php

session_start();
include 'db_connection.php';

// Strict admin check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: app.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: app.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

try {
    $conn->beginTransaction();
    
    // Verify rental belongs to user
    $stmt = $conn->prepare("SELECT scooter_id, rental_end FROM rentals WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$rental) {
        header("Location: app.php?message=Rental not found or access denied");
        exit();
    }
    
    // If rental is active, make scooter available again
    if (!$rental['rental_end']) {
        $stmt = $conn->prepare("UPDATE scooters SET is_available = 1 WHERE id = :scooter_id");
        $stmt->bindParam(':scooter_id', $rental['scooter_id']);
        $stmt->execute();
    }
    
    // Delete rental record
    $stmt = $conn->prepare("DELETE FROM rentals WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    header("Location: index.php?message=Rental deleted successfully");
    exit();
} catch(PDOException $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>