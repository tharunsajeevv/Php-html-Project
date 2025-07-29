<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "turf_booking");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Fetch user details
    $sql = "SELECT id, username, email, password FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["email"] = $row["email"];

            // Redirect to homepage
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Turf Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #000000, #222222);
            color: white;
        }

        .login-container {
            width: 400px;
            padding: 40px;
            background: rgba(17, 17, 17, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 69, 0, 0.5);
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 5px;
            font-size: 16px;
            background: #222;
            color: white;
            border: 2px solid #333;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #ff4500;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.7);
            transform: scale(1.02);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: #ff4500;
            color: white;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .login-btn:hover {
            background: #d63000;
            transform: scale(1.05);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
            color: #aaa;
        }

        .signup-link a {
            color: #ff4500;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 450px) {
            .login-container {
                width: 90%;
                padding: 30px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit" class="login-btn">Login</button>
    </form>

    <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>

</body>
</html>
