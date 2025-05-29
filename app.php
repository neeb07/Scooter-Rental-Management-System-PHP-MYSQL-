<?php
session_start();
include 'db_connection.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scooter Rental System</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('https://images.unsplash.com/photo-1554223789-df81106a45ed?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 1200px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .user-info {
            text-align: right;
            margin-bottom: 30px;
        }

        .user-info a {
            margin-left: 10px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .button.danger {
            background-color: #f44336;
        }

        .button.danger:hover {
            background-color: #d32f2f;
        }

        .button.info {
            background-color: #2196F3;
        }

        .button.info:hover {
            background-color: #0b7dda;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
            Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="admin_dashboard.php" class="button info">Admin Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="button danger">Logout</a>
        </div>

        <h1>Scooter Rental System</h1>

        <div class="card">
            <h2>Rentals - All the Rentals are for 3 hours</h2>
            <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true): ?>
                <a href="add_rental.php" class="button">Create New Rental</a>
            <?php endif; ?>
            <?php include 'list_rentals.php'; ?>
        </div>
    </div>
</body>
</html>
