<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Turf Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
        }

        body {
            background: #000;
            color: #fff;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 50px;
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
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff4500;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: url('images/bg3.jpg') no-repeat center center/cover;
            position: relative;
            z-index: 1;
        }

        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .hero h1 {
            font-size: 5rem;
            font-weight: 700;
            text-transform: uppercase;
            background: -webkit-linear-gradient(45deg, white, #ff4500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.5rem;
            margin-top: 10px;
            font-weight: 300;
            text-transform: uppercase;
        }

        /* Turf Grid */
        .turfs {
            display: flex;
            justify-content: center;
            gap: 30px;
            padding: 80px 50px;
            text-align: center;
        }

        .turf {
            background: #222;
            padding: 20px;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            width: 300px;
            overflow: hidden;
            position: relative;
        }

        .turf:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(255, 69, 0, 0.4);
        }

        .turf img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            filter: grayscale(90%);
            transition: filter 0.3s ease, transform 0.3s ease;
        }

        .turf:hover img {
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        .turf h3 {
            margin-top: 15px;
            font-size: 1.8rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .turf p {
            font-size: 1rem;
            color: #e0e0e0;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 3rem;
            }

            .turfs {
                flex-direction: column;
                align-items: center;
            }

            .navbar {
                flex-direction: column;
                align-items: center;
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

<!-- Hero Section -->
<div class="hero">
    <h1>Rule The Game</h1>
    <p>Find the best turf and own your moment.</p>
</div>

<!-- Turf Grid -->
<div class="turfs">
    <div class="turf" onclick="window.location.href='turf_details.php?turf=1'">
        <img src="images/turf13.jpg" alt="Turf 1">
        <h3>Turf 1</h3>
        <p>Best for Football & Cricket.</p>
    </div>
    <div class="turf" onclick="window.location.href='turf_details.php?turf=2'">
        <img src="images/turf12.jpg" alt="Turf 2">
        <h3>Turf 2</h3>
        <p>Perfect for 5-a-side football.</p>
    </div>
    <div class="turf" onclick="window.location.href='turf_details.php?turf=3'">
        <img src="images/turf6.jpg" alt="Turf 3">
        <h3>Turf 3</h3>
        <p>Spacious for multiple sports.</p>
    </div>
</div>

</body>
</html>
