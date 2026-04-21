<?php

class Review {
    private $conn; // Database connection

    public function __construct($db) {
        $this->conn = $db;
    }

    // Save review for a day
    public function addReview($user_id, $day_id, $rating, $note) {

        $sql = "INSERT INTO reviews (user_id, day_id, rating, note)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$user_id, $day_id, $rating, $note]);
    }

    // Check if review already exists
    public function hasReview($user_id, $day_id) {

        $sql = "SELECT * FROM reviews WHERE user_id = ? AND day_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $day_id]);

        return $stmt->fetch(); // returns data or false
    }
}