<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header("Location: manage_users.php");
}

$result = $conn->query("SELECT id, name, email FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <h2>Manage Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><a href="?delete=<?php echo $row['id']; ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
