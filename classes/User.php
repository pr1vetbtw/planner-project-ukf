<?php

class User {
    private $conn; // Database connection

    public function __construct($db) {
        $this->conn = $db; // Save connection
    }

    // Register new user
    public function register($username, $password) {

        // Hash password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL query to insert user
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql); // Prepare statement

        // Execute query with values
        return $stmt->execute([$username, $hashedPassword]);
    }

    // Login user
    public function login($username, $password) {

        // Find user by username
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);

        $user = $stmt->fetch(); // Get user data

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user if correct
        }

        return false; // Login failed
    }
}