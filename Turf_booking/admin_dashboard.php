<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "turf_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Dashboard Data
$total_bookings = 0;
$total_users = 0;
$total_revenue = 0;

// Total Bookings
$result = $conn->query("SELECT COUNT(*) AS total_bookings FROM bookings");
if ($result) {
    $total_bookings = $result->fetch_assoc()['total_bookings'];
}

// Total Users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
if ($result) {
    $total_users = $result->fetch_assoc()['total_users'];
}

// Total Revenue (Fetching price from turfs table)
$result = $conn->query("SELECT SUM(t.price) AS total_revenue 
                        FROM bookings b
                        JOIN turfs t ON b.turf_id = t.id
                        WHERE b.payment_status = 'paid'");

if ($result) {
    $total_revenue = $result->fetch_assoc()['total_revenue'] ?? 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
        }

        body {
            background: #121212;
            color: white;
            text-align: center;
        }

        .navbar {
            background: #1c1c1c;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
        }

        .navbar .logo {
            font-weight: bold;
            text-transform: uppercase;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            transition: 0.3s;
        }

        .navbar a:hover {
            color: #ff4500;
        }

        .dashboard {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 50px;
        }

        .card {
            background: #222;
            padding: 20px;
            border-radius: 10px;
            width: 200px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        .card h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            color: #ff4500;
        }

        .logout {
            margin-top: 20px;
        }

        .logout a {
            background: #ff4500;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .logout a:hover {
            background: #ff5733;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">Admin Dashboard</div>
    <div>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_turfs.php">Manage Turfs</a>
        <a href="manage_bookings.php">Manage Bookings</a>
        <a href="admin_logout.php">Logout</a>
    </div>
</div>

<!-- Dashboard Stats -->
<div class="dashboard">
    <div class="card">
        <h2>Total Bookings</h2>
        <p><?php echo $total_bookings; ?></p>
    </div>
    <div class="card">
        <h2>Total Users</h2>
        <p><?php echo $total_users; ?></p>
    </div>
    <div class="card">
        <h2>Total Revenue</h2>
        <p>₹<?php echo number_format($total_revenue, 2); ?></p>
    </div>
</div>

<!-- Logout Button -->
<div class="logout">
    <a href="admin_logout.php">Logout</a>
</div>

</body>
</html>
