<?php
try {
    $stmt = $conn->prepare("SELECT * FROM scooters");
    $stmt->execute();
    
    $scooters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($scooters) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Color</th>
                    <th>Max Speed</th>
                    <th>Battery</th>
                    <th>Price/Hour</th>
                    <th>Available</th>
                    <th>Actions</th>
                </tr>";
        
        foreach ($scooters as $scooter) {
            $available = $scooter['is_available'] ? 'Yes' : 'No';
            echo "<tr>
                    <td>{$scooter['id']}</td>
                    <td>{$scooter['model']}</td>
                    <td>{$scooter['color']}</td>
                    <td>{$scooter['max_speed']} km/h</td>
                    <td>{$scooter['battery_level']}%</td>
                    <td>\${$scooter['rental_price']}</td>
                    <td>$available</td>
                    <td>
                        <a href='edit_scooter.php?id={$scooter['id']}' class='button info'>Edit</a>
                        <a href='delete_scooter.php?id={$scooter['id']}' class='button danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No scooters found.</p>";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>