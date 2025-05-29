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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Scooter</title>
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
            padding: 40px 20px;
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
        <h1>Edit Scooter</h1>
        <form action="update_scooter.php" method="post">
            <input type="hidden" name="id" value="<?php echo $scooter['id']; ?>">

            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($scooter['model']); ?>" required>
            </div>

            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($scooter['color']); ?>" required>
            </div>

            <div class="form-group">
                <label for="max_speed">Max Speed (km/h):</label>
                <input type="number" id="max_speed" name="max_speed" value="<?php echo htmlspecialchars($scooter['max_speed']); ?>" required>
            </div>

            <div class="form-group">
                <label for="battery_level">Battery Level (%):</label>
                <input type="number" id="battery_level" name="battery_level" min="0" max="100" value="<?php echo htmlspecialchars($scooter['battery_level']); ?>" required>
            </div>

            <div class="form-group">
                <label for="rental_price">Rental Price (per hour):</label>
                <input type="number" id="rental_price" name="rental_price" step="0.01" value="<?php echo htmlspecialchars($scooter['rental_price']); ?>" required>
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
