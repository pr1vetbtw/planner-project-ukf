<?php

class Task {
    private $conn; // Database connection

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all tasks for a specific user and day
    public function getTasks($user_id, $day_id) {

        $sql = "SELECT * FROM tasks WHERE user_id = ? AND day_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $day_id]);

        return $stmt->fetchAll(); // Return all tasks
    }

    // Add new task
    public function addTask($user_id, $day_id, $title) {

        $sql = "INSERT INTO tasks (user_id, day_id, title) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$user_id, $day_id, $title]);
    }

    // Mark task as completed
    public function completeTask($task_id) {

        $sql = "UPDATE tasks SET status = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$task_id]);
    }

    // Delete task
    public function deleteTask($task_id) {

        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$task_id]);
    }
}