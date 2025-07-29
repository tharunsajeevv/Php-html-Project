<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if turf ID is provided
if (!isset($_GET['turf'])) {
    die("Invalid turf selection.");
}

$turf_id = intval($_GET['turf']);

// Fetch Turf Details
$sql = "SELECT * FROM turfs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $turf_id);
$stmt->execute();
$result = $stmt->get_result();
$turf = $result->fetch_assoc();

if (!$turf) {
    die("Turf not found.");
}

// Fetch Available Time Slots
$time_slots = ["8:00 AM - 9:00 AM", "9:00 AM - 10:00 AM", "10:00 AM - 11:00 AM", "11:00 AM - 12:00 PM"];

// Check if the turf is already booked for any slot
$booked_slots = [];
$sql = "SELECT time_slot FROM bookings WHERE turf_id = ? AND payment_status = 'Paid'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $turf_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $booked_slots[] = $row['time_slot'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $turf['name']; ?> - Turf Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
        }

        body {
            background: #000;
            color: white;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background: #111;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            color: white;
            font-size: 18px;
            text-transform: uppercase;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff4500;
        }

        /* Container */
        .container {
            width: 85%;
            margin: auto;
            padding: 50px 0;
            display: flex;
            gap: 40px;
        }

        .left-section {
            flex: 2;
            background: #111;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .right-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        img {
            width: 100%;
            height: 350px;
            border-radius: 10px;
            object-fit: cover;
        }

        h2, h3 {
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        p {
            font-size: 1rem;
            color: #e0e0e0;
            line-height: 1.6;
        }

        select {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            background: #333;
            color: white;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }

        select option:disabled {
            background: #777;
            color: #ccc;
        }

        .book-btn {
            margin-top: 20px;
            padding: 15px;
            width: 100%;
            border: none;
            background: #ff4500;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .book-btn:hover {
            background: #d63000;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 20px;
            }

            .left-section, .right-section {
                width: 100%;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar .logo {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">Turf Booking</div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <!-- Left Section -->
    <div class="left-section">
        <h2><?php echo $turf['name']; ?></h2>
        <img src="<?php echo htmlspecialchars($turf['image']); ?>" alt="Turf Image">

        <p><strong>Description:</strong> <?php echo $turf['description']; ?></p>
        <p><strong>Price:</strong> ₹<?php echo $turf['price']; ?> per hour</p>

        <h3>Select Time Slot</h3>
        <form method="POST" action="payment.php">
            <input type="hidden" name="turf_id" value="<?php echo $turf_id; ?>">
            <input type="hidden" name="turf_name" value="<?php echo htmlspecialchars($turf['name']); ?>">
            <input type="hidden" name="price" value="<?php echo htmlspecialchars($turf['price']); ?>">
            <select name="time_slot" required>
                <?php foreach ($time_slots as $slot) { ?>
                    <option value="<?php echo htmlspecialchars($slot); ?>" 
                        <?php echo in_array($slot, $booked_slots) ? 'disabled' : ''; ?>>
                        <?php echo htmlspecialchars($slot); ?> 
                        <?php echo in_array($slot, $booked_slots) ? '(Booked)' : ''; ?>
                    </option>
                <?php } ?>
            </select>
            <button type="submit" class="book-btn">Book Now</button>
        </form>
    </div>

    <!-- Right Section -->
    <div class="right-section">
        <div class="card">
            <h3>Sports Available</h3>
            <p><?php echo isset($turf['sports_available']) ? $turf['sports_available'] : 'Not specified'; ?></p>
        </div>
        <div class="card">
            <h3>Amenities</h3>
            <p><?php echo nl2br($turf['amenities']); ?></p>
        </div>
        <div class="card">
            <h3>Location</h3>
            <p><?php echo $turf['location']; ?></p>
        </div>
    </div>
</div>

</body>
</html>
