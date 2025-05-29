<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $model = $_POST['model'];
    $color = $_POST['color'];
    $max_speed = $_POST['max_speed'];
    $battery_level = $_POST['battery_level'];
    $rental_price = $_POST['rental_price'];
    $is_available = $_POST['is_available'];
    
    try {
        $stmt = $conn->prepare("UPDATE scooters SET model = :model, color = :color, max_speed = :max_speed, 
                               battery_level = :battery_level, rental_price = :rental_price, is_available = :is_available 
                               WHERE id = :id");
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':max_speed', $max_speed);
        $stmt->bindParam(':battery_level', $battery_level);
        $stmt->bindParam(':rental_price', $rental_price);
        $stmt->bindParam(':is_available', $is_available);
        
        $stmt->execute();
        
        header("Location: index.php?message=Scooter updated successfully");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>