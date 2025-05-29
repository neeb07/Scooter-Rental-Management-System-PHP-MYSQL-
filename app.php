<?php
session_start();
include 'db_connection.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// This is your original index.php content (the main application)
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scooter Rental System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .button { padding: 8px 12px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
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
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="admin_dashboard.php" class="button info">Admin Dashboard</a>
    <?php endif; ?>
    <a href="logout.php" class="button danger">Logout</a>
</div>
        <div>
<?php if (!empty($_SESSION['is_admin'])): ?>
    <!-- <a href="admin_dashboard.php" class="admin-link">Admin Dashboard</a> -->
<?php endif; ?>
        </div>
        
        <h1>Scooter Rental System</h1>
        
        
        
        <div class="card">
            <h2>Rentals</h2>
            <?php if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true): ?>
                <a href="add_rental.php" class="button">Create New Rental</a>
                    <?php endif; ?>
            <?php include 'list_rentals.php'; ?>
        </div>
    </div>
</body>
</html>