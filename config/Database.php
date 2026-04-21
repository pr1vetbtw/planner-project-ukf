<?php

class Database {
    private $host = "localhost";        // Database host (server)
    private $db_name = "planner_db";    // Database name
    private $username = "root";         // MySQL username 
    private $password = "";             // MySQL password 

    public function connect() {
        try {
            // Create PDO connection
            $conn = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name", // DSN (connection string)
                $this->username,                               // username
                $this->password                                // password
            );

            // Set error mode to exception (important for debugging)
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn; // Return connection object

        } catch (PDOException $e) {
            // Display error if connection fails
            echo "Database connection error: " . $e->getMessage();
        }
    }
}