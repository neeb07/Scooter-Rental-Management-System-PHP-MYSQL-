<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $_POST['model'];
    $color = $_POST['color'];
    $max_speed = $_POST['max_speed'];
    $battery_level = $_POST['battery_level'];
    $rental_price = $_POST['rental_price'];
    $is_available = $_POST['is_available'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO scooters (model, color, max_speed, battery_level, rental_price, is_available) 
                                VALUES (:model, :color, :max_speed, :battery_level, :rental_price, :is_available)");
        
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':max_speed', $max_speed);
        $stmt->bindParam(':battery_level', $battery_level);
        $stmt->bindParam(':rental_price', $rental_price);
        $stmt->bindParam(':is_available', $is_available);
        
        $stmt->execute();
        
        header("Location: index.php?message=Scooter added successfully");
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>