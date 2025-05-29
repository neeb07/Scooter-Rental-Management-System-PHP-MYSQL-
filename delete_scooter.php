<?php
require_once 'check_admin.php';
require_once 'admin_auth.php';
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: app.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$id = $_GET['id'];

try {
    // First check if scooter exists and is available
    $stmt = $conn->prepare("SELECT is_available FROM scooters WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    $scooter = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$scooter) {

        
        header("Location: admin_dashboard.php?error=Scooter+not+found");
        exit();
    }
    
    if ($scooter['is_available'] != 1) {
        
    
        header("Location: admin_dashboard.php?error=Cannot+delete+scooter+that+is+not+available");
        
        exit();
    }
    
    // Then check for active rentals (additional safety)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM rentals WHERE scooter_id = :id AND rental_end IS NULL");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    if ($stmt->fetchColumn() > 0) {
        header("Location: admin_dashboard.php?error=Cannot+delete+scooter+with+active+rentals");
        exit();
    }
    
    // If checks pass, delete the scooter
    $stmt = $conn->prepare("DELETE FROM scooters WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    header("Location: admin_dashboard.php?message=Scooter+deleted+successfully");
    exit();
    
} catch(PDOException $e) {
    header("Location: admin_dashboard.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>