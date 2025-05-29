<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $scooter_id = $_POST['scooter_id'];
    $customer_name = $_SESSION['username'];
    $rental_start = $_POST['rental_start'];
    $user_id = $_SESSION['user_id'];

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO rentals (scooter_id, user_id, customer_name, rental_start) 
                                VALUES (:scooter_id, :user_id, :customer_name, :rental_start)");
        $stmt->bindParam(':scooter_id', $scooter_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':customer_name', $customer_name);
        $stmt->bindParam(':rental_start', $rental_start);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE scooters SET is_available = 0 WHERE id = :scooter_id");
        $stmt->bindParam(':scooter_id', $scooter_id);
        $stmt->execute();

        $conn->commit();

        header("Location: app.php?message=Rental created successfully");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Rental</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1554223789-df81106a45ed?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Rental</h1>
        <form method="post">
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
                <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
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
