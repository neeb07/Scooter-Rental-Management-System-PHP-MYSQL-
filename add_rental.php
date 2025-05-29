<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scooter_id = $_POST['scooter_id'];
    $customer_name = $_SESSION['username']; // Use logged in username
    $rental_start = $_POST['rental_start'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    try {
        $conn->beginTransaction();
        
        // Modified query to include user_id
       // In the rental creation part:
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO rentals (scooter_id, user_id, customer_name, rental_start) 
                       VALUES (:scooter_id, :user_id, :customer_name, :rental_start)");
$stmt->bindParam(':scooter_id', $scooter_id);
$stmt->bindParam(':user_id', $user_id); // Critical - associate with user
$stmt->bindParam(':customer_name', $_SESSION['username']);
$stmt->bindParam(':rental_start', $rental_start);
        $stmt->execute();
        
        $stmt = $conn->prepare("UPDATE scooters SET is_available = 0 WHERE id = :scooter_id");
        $stmt->bindParam(':scooter_id', $scooter_id);
        $stmt->execute();
        
        $conn->commit();
        
        header("Location: app.php?message=Rental created successfully");
        exit();
    } catch(PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- Rest of your existing add_rental.php form -->

<!DOCTYPE html>
<html>
<head>
    <title>Create Rental</title>
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
        <h1>Create New Rental</h1>
        
        <form action="process_rental.php" method="post">
            <div class="form-group">
                <label for="scooter_id">Scooter:</label>
                <select id="scooter_id" name="scooter_id" required>
                    <option value="">Select Scooter</option>
                    <?php
                    try {
                        $stmt = $conn->prepare("SELECT id, model FROM scooters WHERE is_available = 1");
                        $stmt->execute();
                        
                        $scooters = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($scooters as $scooter) {
                            echo "<option value='{$scooter['id']}'>{$scooter['model']}</option>";
                        }
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="customer_name">Customer Name:</label>
                    <input type="hidden" name="rental_start" value="<?php echo date('Y-m-d\TH:i'); ?>">

                <input type="text" id="customer_name" name="customer_name" required>
            </div>
            
            <div class="form-group">
                <label for="rental_start">Rental Start:</label>
                <input type="datetime-local" id="rental_start" name="rental_start" required>
            </div>
            
            <button type="submit" class="button">Create Rental</button>
            <a href="index.php" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>