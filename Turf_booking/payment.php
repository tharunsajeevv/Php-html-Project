<?php
session_start();

if (!isset($_POST['turf_id'], $_POST['turf_name'], $_POST['price'], $_POST['time_slot'])) {
    die("Invalid access.");
}

$_SESSION['turf_id'] = $_POST['turf_id']; 
$_SESSION['turf_name'] = $_POST['turf_name'];
$_SESSION['price'] = $_POST['price'];
$_SESSION['time_slot'] = $_POST['time_slot'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
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
            text-align: center;
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
            transition: background 0.3s ease;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }

        .navbar:hover {
            background: #222;
        }

        .nav-links a {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            margin-right: 20px;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff4500;
        }

        .container {
            background: #222;
            padding: 30px;
            border-radius: 10px;
            width: 40%;
            margin: 50px auto;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        h2 {
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        input, button {
            display: block;
            width: 90%;
            margin: 10px auto;
            padding: 12px;
            border: none;
            border-radius: 5px;
        }

        input {
            background: #333;
            color: white;
            font-size: 1rem;
        }

        button {
            background: #ff4500;
            color: white;
            font-size: 18px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            background: #d63000;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
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

<!-- Payment Form -->
<div class="container">
    <h2>Complete Payment</h2>
    <p><strong>Turf:</strong> <?php echo $_SESSION['turf_name']; ?></p>
    <p><strong>Price:</strong> ₹<?php echo $_SESSION['price']; ?></p>
    <p><strong>Time Slot:</strong> <?php echo $_SESSION['time_slot']; ?></p>

    <form method="POST" action="process_payment.php" onsubmit="return validatePayment()">
        <input type="text" id="card_number" name="card_number" placeholder="Card Number (16 digits)" required maxlength="16">
        <input type="text" id="card_holder" name="card_holder" placeholder="Cardholder Name" required>
        <input type="text" id="expiry_date" name="expiry_date" placeholder="Expiry Date (MM/YY)" required maxlength="5">
        <input type="text" id="cvv" name="cvv" placeholder="CVV (3 digits)" required maxlength="3">
        <button type="submit">Pay Now</button>
    </form>
</div>

<script>
    function validatePayment() {
        let cardNumber = document.getElementById("card_number").value;
        let cardHolder = document.getElementById("card_holder").value;
        let expiryDate = document.getElementById("expiry_date").value;
        let cvv = document.getElementById("cvv").value;

        if (!/^\d{16}$/.test(cardNumber)) {
            alert("Card number must be exactly 16 digits.");
            return false;
        }

        if (!/^[A-Za-z\s]+$/.test(cardHolder)) {
            alert("Cardholder name must contain only letters and spaces.");
            return false;
        }

        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate)) {
            alert("Expiry date must be in MM/YY format.");
            return false;
        }

        if (!/^\d{3}$/.test(cvv)) {
            alert("CVV must be exactly 3 digits.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
