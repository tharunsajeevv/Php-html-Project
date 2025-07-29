<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure session values exist
if (!isset($_SESSION['user_id'], $_SESSION['turf_id'], $_SESSION['time_slot'], $_SESSION['price'])) {
    die("Invalid access.");
}

$user_id = $_SESSION['user_id'];
$turf_id = $_SESSION['turf_id'];
$time_slot = $_SESSION['time_slot'];
$price = $_SESSION['price'];
$transaction_id = "TXN" . time() . rand(1000, 9999);  // Generates unique transaction ID

// Check if the time slot is already booked
$sql_check = "SELECT id FROM bookings WHERE turf_id = ? AND time_slot = ? AND payment_status = 'Paid'";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("is", $turf_id, $time_slot);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    header("Location: payment_success.php?already_booked=1");
    exit();
}

// Insert new booking
$booking_sql = "INSERT INTO bookings (user_id, turf_id, time_slot, payment_status, booking_date, booking_time) 
                VALUES (?, ?, ?, 'Paid', CURDATE(), NOW())";

$stmt = $conn->prepare($booking_sql);
$stmt->bind_param("iis", $user_id, $turf_id, $time_slot);
if (!$stmt->execute()) {
    die("Booking Insert Error: " . $stmt->error);
}

$booking_id = $stmt->insert_id;

// Check if the transaction_id already exists (to avoid duplicates)
$sql_check_transaction = "SELECT transaction_id FROM payment WHERE transaction_id = ?";
$stmt_check_transaction = $conn->prepare($sql_check_transaction);
$stmt_check_transaction->bind_param("s", $transaction_id);
$stmt_check_transaction->execute();
$stmt_check_transaction->store_result();

if ($stmt_check_transaction->num_rows > 0) {
    die("Error: Duplicate Transaction ID detected.");
}

// Insert payment details
$payment_sql = "INSERT INTO payment (booking_id, transaction_id, amount, payment_status, payment_time) 
                VALUES (?, ?, ?, 'Paid', NOW())";

$stmt = $conn->prepare($payment_sql);
$stmt->bind_param("isd", $booking_id, $transaction_id, $price);
if (!$stmt->execute()) {
    die("Payment Insert Error: " . $stmt->error);
}

// Redirect to success page with transaction ID
header("Location: payment_success.php?transaction_id=" . urlencode($transaction_id));
exit();

