<?php
// session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Log unauthorized access attempt
    error_log("Unauthorized admin access attempt by user ID: " . ($_SESSION['user_id'] ?? 'unknown'));
    header("HTTP/1.1 403 Forbidden");
    die("Access denied. You don't have permission to access this page.");
}
?>