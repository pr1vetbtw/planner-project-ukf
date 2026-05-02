<?php

session_start(); // Start session

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Allow only admin users
if ($_SESSION["role"] !== "admin") {
    echo "Access denied";
    exit();
}

require_once "../config/Database.php";
require_once "../classes/Task.php";

$db = new Database();
$conn = $db->connect();

$task = new Task($conn);

// Get all users
$users = $conn->query("SELECT id, username FROM users")->fetchAll();

// Get all days
$days = $conn->query("SELECT * FROM days")->fetchAll();

// Selected user and day
$selected_user = $_GET["user_id"] ?? null;
$day_id = $_GET["day_id"] ?? 1;

// Delete task
if (isset($_GET["delete"])) {
    $task->deleteTask($_GET["delete"]);
}

// Update task
if (isset($_POST["update_task"])) {
    $stmt = $conn->prepare("UPDATE tasks SET title=? WHERE id=?");
    $stmt->execute([$_POST["title"], $_POST["task_id"]]);
}

// Get tasks
$tasks = [];
if ($selected_user) {
    $tasks = $task->getTasks($selected_user, $day_id);
}

// Stats
$total = count($tasks);
$completed = 0;

foreach ($tasks as $t) {
    if ($t['status'] == 1) $completed++;
}

$percent = $total > 0 ? ($completed / $total) * 100 : 0;

?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<style>
body {
    background: linear-gradient(135deg, #2c2c2c, #3f3f3f);
    min-height: 100vh;
    color: white;
}

/* Main container */
.admin-box {
    max-width: 950px;
    margin: 50px auto;
    background: #444;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    animation: fadeIn 0.4s ease-in-out;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px);}
    to { opacity: 1; transform: translateY(0);}
}

/* Titles */
h3, h5 {
    text-align: center;
}

/* User list */
.list-group-item {
    background: #555;
    color: white;
    border: none;
    transition: 0.2s;
}

.list-group-item:hover {
    background: #28a745;
    transform: scale(1.02);
}

/* Inputs */
input {
    background: #555 !important;
    color: white !important;
    border: none !important;
}

/* Buttons */
.btn-red {
    background: linear-gradient(135deg, #dc3545, #c82333);
    border: none;
}

.btn-red:hover {
    transform: scale(1.05);
}

.btn-warning {
    transition: 0.2s;
}

.btn-warning:hover {
    transform: scale(1.05);
}

/* Day buttons */
.btn-outline-light {
    border-radius: 10px;
    transition: 0.2s;
}

.btn-outline-light:hover {
    background: #28a745;
    border-color: #28a745;
}

/* Progress */
.progress {
    background: #555;
}

/* Back button */
.back-btn {
    width: 200px;
    margin: 20px auto 0;
    display: block;
}
</style>

</head>

<body>

<div class="admin-box">

<h3 class="mb-4">⚙️ Admin Panel</h3>

<!-- USERS -->
<h5>Select User</h5>
<ul class="list-group mb-3">
<?php foreach ($users as $u): ?>
<li class="list-group-item text-center">
<a href="?user_id=<?php echo $u['id']; ?>&day_id=<?php echo $day_id; ?>" style="color:white; text-decoration:none;">
<?php echo $u['username']; ?>
</a>
</li>
<?php endforeach; ?>
</ul>

<!-- DAYS -->
<?php if ($selected_user): ?>
<h5 class="mt-3">Select Day</h5>

<div class="mb-3 text-center">
<?php foreach ($days as $d): ?>
<a href="?user_id=<?php echo $selected_user; ?>&day_id=<?php echo $d['id']; ?>"
class="btn btn-outline-light m-1">
<?php echo $d['name']; ?>
</a>
<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- TASKS -->
<?php if ($selected_user): ?>

<h5>Tasks</h5>

<!-- STATS -->
<div class="mb-3 text-center">
<p>Total: <?php echo $total; ?></p>
<p>Completed: <?php echo $completed; ?></p>

<div class="progress">
<div class="progress-bar bg-success"
style="width: <?php echo $percent; ?>%">
<?php echo round($percent); ?>%
</div>
</div>
</div>

<ul class="list-group">
<?php foreach ($tasks as $t): ?>
<li class="list-group-item d-flex justify-content-between align-items-center">

<form method="POST" class="d-flex w-100">
<input type="hidden" name="task_id" value="<?php echo $t['id']; ?>">

<input type="text" name="title"
value="<?php echo $t['title']; ?>"
class="form-control me-2">

<button name="update_task" class="btn btn-warning btn-sm me-2">
Update
</button>

<a class="btn btn-red btn-sm"
href="?user_id=<?php echo $selected_user; ?>&day_id=<?php echo $day_id; ?>&delete=<?php echo $t['id']; ?>">
Delete
</a>
</form>

</li>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<a href="dashboard.php" class="btn btn-secondary back-btn">
Back
</a>

</div>

</body>
</html>