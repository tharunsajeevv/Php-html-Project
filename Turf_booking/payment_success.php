<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$turf_name = $_SESSION['turf_name'] ?? 'Unknown';
$price = $_SESSION['price'] ?? 'Unknown';
$time_slot = $_SESSION['time_slot'] ?? 'Unknown';

// Check if booking already exists
$alreadyBooked = isset($_GET['already_booked']) && $_GET['already_booked'] == 1;

// Retrieve Transaction ID
$transaction_id = $_GET['transaction_id'] ?? 'N/A';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
        }
        body {
            background: #0d0d0d;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: #222;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
            text-align: center;
            position: relative;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .success-icon {
            font-size: 50px;
            color: #32CD32;
            margin-bottom: 15px;
        }
        .error-icon {
            font-size: 50px;
            color: #FF4500;
            margin-bottom: 15px;
        }
        h2 {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        p {
            font-size: 1rem;
            color: #e0e0e0;
            line-height: 1.6;
            margin: 5px 0;
        }
        .details {
            margin-top: 20px;
            padding: 10px;
            background: #333;
            border-radius: 5px;
        }
        .btn {
            display: block;
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
        .btn:hover {
            background: #d63000;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($alreadyBooked) { ?>
        <div class="error-icon">❌</div>
        <h2>Booking Already Exists!</h2>
        <p>You have already booked this turf at this time slot.</p>
    <?php } else { ?>
        <div class="success-icon">✅</div>
        <h2>Payment Successful!</h2>
        <p>Your booking has been confirmed.</p>
    <?php } ?>

    <div class="details">
        <p><strong>Turf:</strong> <?php echo $turf_name; ?></p>
        <p><strong>Price:</strong> ₹<?php echo $price; ?></p>
        <p><strong>Time Slot:</strong> <?php echo $time_slot; ?></p>
        <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_id); ?></p>
    </div>

    <button class="btn" onclick="window.location.href='index.php'">Go to Home</button>
</div>

</body>
</html>
