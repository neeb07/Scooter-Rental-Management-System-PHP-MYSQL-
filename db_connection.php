<?php
$host = 'localhost';
$dbname = 'scooterrental';
$username = 'root'; // Change as per your setup
$password = 'root';     // Change as per your setup

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>