<?php
if (!isset($_SESSION['user_id'])) {
    die("Access denied - please login");}

    $user_id = $_SESSION['user_id'];
    $is_admin = $_SESSION['is_admin'] ?? false;


try {
    $stmt = $conn->prepare("SELECT r.*, s.model, s.rental_price FROM rentals r JOIN scooters s ON r.scooter_id = s.id");
    $stmt->execute();
    
    $rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($rentals) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Scooter</th>";
        
        // Show Customer column only for admins
        if ($is_admin) {
            echo "<th>Customer</th>";
        }

        echo "  <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Cost</th>
                    <th>Status</th>";
        if ($is_admin) {
            echo "<th>Actions</th>";
        }";
                </tr>";

    
        
        foreach ($rentals as $rental) {
            $end_time = $rental['rental_end'] ? date('Y-m-d H:i', strtotime($rental['rental_end'])) : 'Active';
            $total_cost = $rental['total_cost'] ? '$' . $rental['total_cost'] : '-';
            $status = $rental['rental_end'] ? 'Completed' : 'Active';
            
            echo "<tr>
                    <td>{$rental['id']}</td>
                    <td>{$rental['model']}</td>";
            
            // Show Customer name only for admins
            if ($is_admin) {
                echo "<td>{$rental['customer_name']}</td>";
            }

            echo "<td>" . date('Y-m-d H:i', strtotime($rental['rental_start'])) . "</td>
                  <td>$end_time</td>
                  <td>$total_cost</td>
                  <td>$status</td>";
             if ($is_admin) {
                echo "<td>";
                if (!$rental['rental_end']) {
                    echo "<a href='end_rental.php?id={$rental['id']}' class='button info'>End Rental</a> ";
                }
                echo "<a href='delete_rental.php?id={$rental['id']}' class='button danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                echo "</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No rentals found.</p>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>