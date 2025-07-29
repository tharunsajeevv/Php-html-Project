<?php
session_start();
$conn = new mysqli("localhost", "root", "", "turf_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();

// Handle profile update
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($new_password) {
        $update_sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $new_name, $new_email, $new_password, $user_id);
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $new_name, $new_email, $user_id);
    }

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $_SESSION['success_message'] = $success;
        header("Location: profile.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Oswald', sans-serif;
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
            border-radius: 10px;
            width: 50%;
            margin: auto;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
        }

        h2 {
            margin-bottom: 20px;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        .success {
            background: #32CD32;
            color: white;
        }

        .error {
            background: #FF4500;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 2px solid #444;
            border-radius: 5px;
            font-size: 16px;
            background: #333;
            color: white;
        }

        input:focus {
            border-color: #ff4500;
            outline: none;
        }

        button {
            background: #ff4500;
            padding: 15px;
            width: 100%;
            border: none;
            color: white;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
        }

        button:hover {
            background: #d63000;
        }

        a {
            color: #ff4500;
            text-decoration: none;
            display: block;
            margin-top: 15px;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Profile</h2>

    <?php if (!empty($success)) { ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php } ?>
    
    <?php if (!empty($error)) { ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label>New Password (leave blank if not changing):</label>
        <input type="password" name="password">

        <button type="submit">Update Profile</button>
    </form>

    <a href="profile.php">Back to Profile</a>
</div>

</body>
</html>
