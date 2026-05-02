<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../classes/Task.php";

$db = new Database();
$conn = $db->connect();

$task = new Task($conn);

// Get all days
$days = $conn->query("SELECT * FROM days")->fetchAll();

// Get current day
$currentDayName = date('l');

$stmt = $conn->prepare("SELECT id FROM days WHERE name = ?");
$stmt->execute([$currentDayName]);
$currentDay = $stmt->fetch();

$todayTasks = [];

if ($currentDay) {
    $todayTasks = $task->getTasks($_SESSION["user_id"], $currentDay['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<style>
body {
    background: linear-gradient(135deg, #2c2c2c, #3f3f3f);
    min-height: 100vh;
    color: white;
}

.dashboard-card {
    max-width: 600px;
    margin: 60px auto;
    border-radius: 20px;
    background: #444;
    padding: 25px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}

.day-btn {
    display: block;
    width: 100%;
    margin: 8px 0;
    padding: 12px;
    border-radius: 10px;
    background: #555;
    text-decoration: none;
    color: white;
    text-align: center;
    transition: 0.3s;
}

.day-btn:hover {
    background: #ff3b3b;
    transform: scale(1.03);
}

.logout-btn {
    width: 60%;
    margin: 20px auto 0;
    display: block;
}

.btn-main {
    background: linear-gradient(135deg, #28a745, #1ecb24);
    border: none;
    width: 100%;
    margin-top: 15px;
    color: white;
    font-weight: 600;
    padding: 10px;
    border-radius: 10px;
    transition: 0.25s;
}

.btn-main:hover {
    transform: scale(1.03);
    box-shadow: 0 0 15px rgba(0, 245, 8, 0.87);
}

.hidden-box {
    margin-top: 15px;
    display: none;
}

.list-group-item {
    background: #555;
    color: white;
    border: none;
}
</style>

</head>

<body>

<div class="dashboard-card">

    <h3 class="text-center mb-4">📅 Planner</h3>

    <!-- Days -->
    <?php foreach ($days as $day): ?>
        <a class="day-btn" href="tasks.php?day_id=<?php echo $day['id']; ?>">
            <?php echo $day['name']; ?>
        </a>
    <?php endforeach; ?>

    <!-- BUTTON -->
    <button class="btn btn-main" onclick="toggleToday()">
         What's left today?
    </button>

    <!-- HIDDEN BLOCK -->
    <div id="todayBox" class="hidden-box">

        <h5 class="text-center mt-3">
            <?php echo $currentDayName; ?>
        </h5>

        <?php 
        $hasPending = false;
        foreach ($todayTasks as $t) {
            if ($t['status'] == 0) $hasPending = true;
        }
        ?>

        <?php if (!$todayTasks || !$hasPending): ?>
            <p class="text-center text-success">
                You are free today 😎
            </p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($todayTasks as $t): ?>
                    <?php if ($t['status'] == 0): ?>
                        <li class="list-group-item text-center">
                            <?php echo $t['title']; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

    <a href="logout.php" class="btn btn-danger logout-btn">
        Logout
    </a>

</div>

<script>
function toggleToday() {
    let box = document.getElementById("todayBox");
    box.style.display = (box.style.display === "none") ? "block" : "none";
}
</script>

</body>
</html>