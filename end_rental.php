<?php
session_start();
include 'db_connection.php';

if (!isset($_GET['id'])) {
    header("Location: app.php?error=invalid_request");
    exit();
}

$rental_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'] ?? false;


try {
    $conn->beginTransaction();
    
    // Different query for admin vs regular users
    if ($is_admin) {
        // Admin can end any rental
        $stmt = $conn->prepare("SELECT r.*, s.rental_price 
                               FROM rentals r
                               JOIN scooters s ON r.scooter_id = s.id
                               WHERE r.id = ?");
    } else {
        // Regular users can only end their own rentals
        $stmt = $conn->prepare("SELECT r.*, s.rental_price 
                               FROM rentals r
                               JOIN scooters s ON r.scooter_id = s.id
                               WHERE r.id = ? AND r.user_id = ?");
    }
    
    $params = $is_admin ? [$rental_id] : [$rental_id, $user_id];
    $stmt->execute($params);
    
    $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rental['rental_end']) {
        throw new Exception("Rental already ended");
    }
    if (!$rental) {
        throw new Exception("Rental not found or access denied");
    }
    
    
    
    // Calculate duration and cost
    $start = new DateTime($rental['rental_start']);
    $end = new DateTime();
    $hours = $end->diff($start)->h + ($end->diff($start)->days * 24);
    $total_cost = round($hours * $rental['rental_price'], 2);
    
    // Update rental
    $stmt = $conn->prepare("UPDATE rentals 
                           SET rental_end = NOW(), total_cost = ?
                           WHERE id = ?");
    $stmt->execute([$total_cost, $rental_id]);
    
    // Mark scooter as available
    $stmt = $conn->prepare("UPDATE scooters 
                           SET is_available = 1 
                           WHERE id = ?");
    $stmt->execute([$rental['scooter_id']]);
    
    $conn->commit();
    
    header("Location: " . ($is_admin ? "app.php" : "app.php") . "?message=Rental+ended+successfully");
    exit();
    
} catch (Exception $e) {
    $conn->rollBack();
    header("Location: " . ($is_admin ? "app.php" : "app.php") . "?error=" . urlencode($e->getMessage()));
    exit();
}
?>