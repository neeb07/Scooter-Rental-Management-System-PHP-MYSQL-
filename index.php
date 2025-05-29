<?php
session_start();
include 'db_connection.php';

// If user is already logged in, redirect to main application
if (isset($_SESSION['user_id'])) {
    header("Location: app.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scooter Rental - Login</title>
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Background Image */
        body {
            background: url('https://images.unsplash.com/photo-1554223789-df81106a45ed?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container */
        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        p {
            margin-bottom: 30px;
            font-size: 16px;
            color: #333;
        }

        .button {
            display: inline-block;
            width: 48%;
            padding: 12px 0;
            margin: 5px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
            background-color: #4CAF50;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .button.register {
            background-color: #2196F3;
        }

        .button.register:hover {
            background-color: #1976D2;
        }

        @media (max-width: 480px) {
            .button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Scooter Rental System</h1>
        <p>Please login or register to continue</p>

        <a href="login.php" class="button">Login</a>
        <a href="register.php" class="button register">Register</a>
    </div>
</body>
</html>
