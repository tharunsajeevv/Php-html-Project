<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "turf_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<span class='error'> Email already exists!</span>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user, $email, $pass);

        if ($stmt->execute()) {
            $message = "<span class='success'> Signup successful! <a href='login.php'>Login here</a></span>";
        } else {
            $message = "<span class='error'>❌ Error: " . $conn->error . "</span>";
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Turf Booking</title>
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
            background: #000;
            color: white;
        }

        .signup-container {
            width: 400px;
            background: #111;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255, 69, 0, 0.4);
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }

        .message {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }

        .success {
            color: #32CD32;
        }

        .error {
            color: #e74c3c;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 5px;
            font-size: 16px;
            background: #222;
            color: white;
            border: 2px solid #333;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #ff4500;
            box-shadow: 0 0 10px rgba(255, 69, 0, 0.6);
        }

        .signup-btn {
            width: 100%;
            padding: 14px;
            background: #ff4500;
            color: white;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background 0.3s ease, transform 0.2s ease;
            text-transform: uppercase;
        }

        .signup-btn:hover {
            background-color: #d63000;
            transform: scale(1.05);
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
            color: white;
        }

        .login-link a {
            color: #ff4500;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Signup</h2>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="signup-btn">Register</button>
    </form>

    <div class="message"><?php echo $message; ?></div>

    <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
