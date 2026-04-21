<?php

require_once "../config/Database.php";
require_once "../classes/User.php";

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

$message = "";

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($user->register($username, $password)) {
        $message = "Registration successful!";
    } else {
        $message = "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #060606cf, #3d3f3d);
            height: 100vh;
            margin: 0;
        }

        .register-wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-card {
            width: 380px;
            border-radius: 15px;
            background: white;
        }

        .btn-green {
            background-color: #28a745;
            border: none;
            padding: 10px;
            width: 60%;
            display: block;
            margin: 0 auto;
        }

        .btn-green:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

<div class="register-wrapper">

    <div class="card p-4 shadow register-card">

        <h3 class="text-center mb-4">Create Account</h3>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info text-center">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input type="text" 
                   name="username" 
                   class="form-control mb-3" 
                   placeholder="Username" 
                   required>

            <input type="password" 
                   name="password" 
                   class="form-control mb-3" 
                   placeholder="Password" 
                   required>

            <button type="submit" class="btn btn-green text-white">
                Register
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="login.php">Back to login</a>
        </div>

    </div>

</div>

</body>
</html>