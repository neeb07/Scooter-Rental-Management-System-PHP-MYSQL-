<?php
session_start();
include 'db_connection.php';

// Strict admin check - redirect non-admins immediately
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("HTTP/1.1 403 Forbidden");
    header("Location: app.php?error=access_denied");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 30px; border-radius: 5px; }
        .button { padding: 15px; background-color: #4CAF50; color: white; border: none; border-radius: 10px; cursor: pointer; text-decoration: none; }
        .button:hover { background-color: #45a049; }
        .button.danger { background-color: #f44336; }
        .button.danger:hover { background-color: #d32f2f; }
        .button.info { background-color: #2196F3; }
        .button.info:hover { background-color: #0b7dda; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 20px; text-align: left; }
        th { background-color: #f2f2f2; }
        .user-info { float: right; margin-bottom: 20px; }
    </style>
</head>
<body>
        
        <div class="container">
        <div class="user-info">
            Welcome Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            <a href="logout.php" class="button danger">Logout</a>
            <a href="app.php" class="button">User View</a>
        </div>
        
        <h1>Admin Dashboard</h1>
        
        <!-- Scooter Management -->
        <div class="card">
            <h2>Scooter Management</h2>
            <a href="add_scooter.php" class="button">Add New Scooter</a>
            <?php include 'list_scooters.php'; ?>
        </div>
        
        <!-- Optional: Add user management if needed -->
    </div>
</body>
</html>