<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];
    $conn->query("DELETE FROM bookings WHERE id = $booking_id");
    header("Location: manage_bookings.php");
}

$result = $conn->query("SELECT b.id, u.name AS user_name, t.name AS turf_name, b.booking_date, b.time_slot, b.payment_status 
                        FROM bookings b
                        JOIN users u ON b.user_id = u.id
                        JOIN turfs t ON b.turf_id = t.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
</head>
<body>
    <h2>Manage Bookings</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Turf</th>
            <th>Date</th>
            <th>Time Slot</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['turf_name']; ?></td>
                <td><?php echo $row['booking_date']; ?></td>
                <td><?php echo $row['time_slot']; ?></td>
                <td><?php echo $row['payment_status']; ?></td>
                <td><a href="?delete=<?php echo $row['id']; ?>">Cancel</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
