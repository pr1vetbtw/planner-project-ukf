<?php

session_start(); // Start session

require_once "../config/Database.php";
require_once "../classes/User.php";

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

$message = "";

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Try to login
    $loggedUser = $user->login($username, $password);

    if ($loggedUser) {

        $_SESSION["user_id"] = $loggedUser["id"];

        header("Location: dashboard.php");
        exit();

    } else {
        $message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #060606cf, #3d3f3d); /* soft grey gradient */
            height: 100vh;
            margin: 0;
        }

        .login-wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
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

<div class="login-wrapper">

    <div class="card p-4 shadow login-card">

        <h3 class="text-center mb-4">Login</h3>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger text-center">
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
                Login
            </button>

        </form>

        <div class="text-center mt-3">
            <a href="register.php">Create account</a>
        </div>

    </div>

</div>

</body>
</html>