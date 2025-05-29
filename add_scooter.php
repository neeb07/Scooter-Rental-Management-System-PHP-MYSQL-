<?php 
require_once 'check_admin.php';
require_once 'admin_auth.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'db_connection.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: app.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Scooter</title>
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
        <h1>Add New Scooter</h1>
        <form action="process_scooter.php" method="post">
            <div class="form-group">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>

            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required>
            </div>

            <div class="form-group">
                <label for="max_speed">Max Speed (km/h):</label>
                <input type="number" id="max_speed" name="max_speed" required>
            </div>

            <div class="form-group">
                <label for="battery_level">Battery Level (%):</label>
                <input type="number" id="battery_level" name="battery_level" min="0" max="100" required>
            </div>

            <div class="form-group">
                <label for="rental_price">Rental Price (per hour):</label>
                <input type="number" id="rental_price" name="rental_price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="is_available">Available:</label>
                <select id="is_available" name="is_available">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <button type="submit" class="button">Add Scooter</button>
            <a href="admin_dashboard.php" class="button">Cancel</a>
        </form>
    </div>
</body>
</html>
