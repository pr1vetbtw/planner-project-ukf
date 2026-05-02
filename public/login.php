<?php

session_start(); // Start session

require_once "../config/Database.php";
require_once "../classes/User.php";

$db = new Database();
$conn = $db->connect();

$user = new User($conn);

$message = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $loggedUser = $user->login($username, $password);

    if ($loggedUser) {

        $_SESSION["user_id"] = $loggedUser["id"];
        $_SESSION["role"] = $loggedUser["role"];

        // Redirect based on role
        if ($loggedUser["role"] === "admin") {
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }

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
    background: linear-gradient(135deg, #2c2c2c, #3f3f3f);
    height: 100vh;
    margin: 0;
    color: white;
}

/* Centering */
.login-wrapper {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card */
.login-card {
    width: 380px;
    border-radius: 20px;
    background: #444;
    padding: 25px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    animation: fadeIn 0.4s ease-in-out;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px);}
    to { opacity: 1; transform: translateY(0);}
}

/* Title */
h3 {
    text-align: center;
}

/* Inputs */
input {
    background: #555 !important;
    color: white !important;
    border: none !important;
}

/* Button */
.btn-green {
    background: linear-gradient(135deg, #28a745, #1ecb24);
    border: none;
    padding: 10px;
    width: 60%;
    display: block;
    margin: 0 auto;
    transition: 0.2s;
}

.btn-green:hover {
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(30, 203, 36, 0.5);
}

/* Link */
a {
    color: #1ecb24;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Alert */
.alert {
    animation: fadeIn 0.3s ease-in-out;
}
</style>
</head>

<body>

<div class="login-wrapper">

<div class="login-card">

<h3 class="mb-4">🔐 Login</h3>

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