<?php
require_once 'check_admin.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

// Strict admin check
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: app.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM scooters WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    $scooter = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$scooter) {
        header("Location: index.php?message=Scooter not found");
        exit();
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Scooter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        .button { padding: 8px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Scooter</h1>
        
        <form action="update_scooter.php" method="post">
            <input type="hidden" name="id" value="<?php echo $scooter['id']; ?>">
            
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo $scooter['model']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" value="<?php echo $scooter['color']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="max_speed">Max Speed (km/h):</label>
                <input type="number" id="max_speed" name="max_speed" value="<?php echo $scooter['max_speed']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="battery_level">Battery Level (%):</label>
                <input type="number" id="battery_level" name="battery_level" min="0" max="100" value="<?php echo $scooter['battery_level']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="rental_price">Rental Price (per hour):</label>
                <input type="number" id="rental_price" name="rental_price" step="0.01" value="<?php echo $scooter['rental_price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="is_available">Available:</label>
                <select id="is_available" name="is_available">
                    <option value="1" <?php echo $scooter['is_available'] ? 'selected' : ''; ?>>Yes</option>
                    <option value="0" <?php echo !$scooter['is_available'] ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            
            <button type="submit" class="button">Update Scooter</button>
            <a href="admin_dashboard.php" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>