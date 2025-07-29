<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch booking history
$sql = "SELECT b.booking_date, b.time_slot, b.payment_status, t.name AS turf_name 
        FROM bookings b
        LEFT JOIN turfs t ON b.turf_id = t.id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Turf Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: #111;
            color: white;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: #222;
            padding: 30px;
            border-radius: 12px;
            width: 60%;
            margin: auto;
            box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.1);
        }
        h2 {
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
            font-size: 18px;
            color: #bbb;
        }
        .bookings {
            margin-top: 30px;
            text-align: left;
        }
        .bookings h3 {
            text-align: center;
            margin-bottom: 15px;
            color: #ff4500;
        }
        .booking {
            background: #333;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(255, 255, 255, 0.1);
        }
        .edit-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background: #ff4500;
            color: white;
            border: none;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .edit-btn:hover {
            background: #d63000;
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
    <p class="info"><?php echo htmlspecialchars($user['email']); ?></p>
    <a href="edit_profile.php" class="edit-btn">Edit Profile</a>

    <div class="bookings">
        <h3>Booking History</h3>
        <?php if ($bookings->num_rows > 0) { 
            while ($row = $bookings->fetch_assoc()) { ?>
                <div class="booking">
                    <p><strong>Turf:</strong> <?php echo htmlspecialchars($row['turf_name']); ?></p>
                    <p><strong>Date:</strong> <?php echo $row['booking_date']; ?></p>
                    <p><strong>Time Slot:</strong> <?php echo $row['time_slot']; ?></p>
                    <p><strong>Status:</strong> <?php echo $row['payment_status']; ?></p>
                </div>
            <?php }
        } else { ?>
            <p>No bookings found.</p>
        <?php } ?>
    </div>
</div>

</body>
</html>
