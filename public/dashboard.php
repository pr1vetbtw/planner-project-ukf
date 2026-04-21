<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/Database.php";

$db = new Database();
$conn = $db->connect();

// Get all days
$sql = "SELECT * FROM days";
$stmt = $conn->query($sql);
$days = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #060606cf, #3d3f3d);
            min-height: 100vh;
        }

        .dashboard-card {
            max-width: 500px;
            margin: 80px auto;
            border-radius: 20px;
            background: white;
            padding: 25px;
        }

        .day-btn {
            display: block;
            width: 100%;
            margin: 8px 0;
            padding: 12px;
            border-radius: 10px;
            background-color: #f8f9fa;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            text-align: center;
            transition: 0.3s;
        }

        .day-btn:hover {
            background-color: #28a745;
            color: white;
            transform: scale(1.03);
        }

        .logout-btn {
            width: 60%;
            margin: 20px auto 0;
            display: block;
        }

        h3 {
            font-weight: 600;
        }
    </style>
</head>

<body>

<div class="dashboard-card shadow">

    <h3 class="text-center mb-4">📅 Planner</h3>

    <?php foreach ($days as $day): ?>
        <a class="day-btn" href="tasks.php?day_id=<?php echo $day['id']; ?>">
            <?php echo $day['name']; ?>
        </a>
    <?php endforeach; ?>

    <a href="logout.php" class="btn btn-danger logout-btn">
        Logout
    </a>

</div>

</body>
</html>