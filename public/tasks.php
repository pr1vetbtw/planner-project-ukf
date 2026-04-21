<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/Database.php";
require_once "../classes/Task.php";
require_once "../classes/Review.php";

$db = new Database();
$conn = $db->connect();

$task = new Task($conn);
$review = new Review($conn);

$user_id = $_SESSION["user_id"];
$day_id = $_GET["day_id"];

// Add task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["title"])) {
    $task->addTask($user_id, $day_id, $_POST["title"]);
}

// Finish day
if (isset($_POST["rating"])) {
    $review->addReview($user_id, $day_id, $_POST["rating"], $_POST["note"]);

    header("Location: tasks.php?day_id=" . $day_id . "&success=1");
    exit();
}

// Actions
if (isset($_GET["complete"])) {
    $task->completeTask($_GET["complete"]);
}
if (isset($_GET["delete"])) {
    $task->deleteTask($_GET["delete"]);
}

$tasks = $task->getTasks($user_id, $day_id);
$hasReview = $review->hasReview($user_id, $day_id);

// Reset day
if (isset($_GET["reset"])) {

    // Delete review (open the day again)
    $stmt = $conn->prepare("DELETE FROM reviews WHERE user_id = ? AND day_id = ?");
    $stmt->execute([$user_id, $day_id]);

    header("Location: tasks.php?day_id=" . $day_id);
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #060606cf, #3d3f3d);
            min-height: 100vh;
        }

        .container-box {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-green {
            background-color: #28a745;
            border: none;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        .center-btn {
            display: block;
            margin: 10px auto;
            width: 60%;
        }

        .task-done {
            text-decoration: line-through;
            color: gray;
        }
    </style>
</head>

<body>

<div class="container-box shadow">

    <h3 class="text-center mb-3">📋 Tasks</h3>

    <!-- SUCCESS MESSAGE -->
    <?php if (isset($_GET["success"])): ?>
        <div class="alert alert-success text-center" id="successAlert">
            🎉 Day completed! You are awesome 😎
        </div>
    <?php endif; ?>

    <!-- ADD TASK -->
    <form method="POST" class="d-flex mb-3">
        <input type="text" name="title" class="form-control me-2" placeholder="New task" required>
        <button class="btn btn-green text-white">Add</button>
    </form>

    <?php
    $total = count($tasks);
    $completed = 0;

    foreach ($tasks as $t) {
        if ($t['status'] == 1) $completed++;
    }

    $percent = $total > 0 ? ($completed / $total) * 100 : 0;
    ?>

    <!-- PROGRESS -->
    <p class="text-center">
        Completed: <?php echo $completed; ?> / <?php echo $total; ?>
    </p>

    <div class="progress mb-3">
        <div class="progress-bar bg-success" style="width: <?php echo $percent; ?>%">
            <?php echo round($percent); ?>%
        </div>
    </div>

    <!-- TASK LIST -->
    <ul class="list-group mb-3">
        <?php foreach ($tasks as $t): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">

                <span class="<?php echo $t['status'] ? 'task-done' : ''; ?>">
                    <?php echo $t['title']; ?>
                </span>

                <div>
                    <?php if ($t['status'] == 0): ?>
                        <a class="btn btn-sm btn-success"
                           href="?day_id=<?php echo $day_id; ?>&complete=<?php echo $t['id']; ?>">
                           ✔
                        </a>
                    <?php endif; ?>

                    <a class="btn btn-sm btn-danger"
                       href="?day_id=<?php echo $day_id; ?>&delete=<?php echo $t['id']; ?>">
                       ✖
                    </a>
                </div>

            </li>
        <?php endforeach; ?>
    </ul>

    <!-- FINISH DAY -->
    <?php if (!$hasReview): ?>

        <form method="POST">
            <input type="number" name="rating" class="form-control mb-2" min="1" max="5" placeholder="Rate your day (1-5)" required>
            <textarea name="note" class="form-control mb-2" placeholder="What was easy or hard?" required></textarea>
            <button class="btn btn-primary center-btn">Finish Day</button>
        </form>

        <?php else: ?>

    <p class="text-success text-center mb-2">✔ Day completed</p>

    <a href="?day_id=<?php echo $day_id; ?>&reset=1"
       class="btn btn-warning center-btn">
       🔄 Reset Day
    </a>

        <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary center-btn">Back</a>

</div>

<!-- AUTO HIDE ALERT -->
<script>
setTimeout(() => {
    let alert = document.getElementById('successAlert');
    if (alert) alert.style.display = 'none';
}, 3000);
</script>

</body>
</html>