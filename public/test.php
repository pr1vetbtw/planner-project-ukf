<?php

require_once "../config/Database.php"; // Include Database class

$db = new Database();        // Create Database object
$conn = $db->connect();      // Call connect() method

if ($conn) {
    echo "Database connection successful!"; // If connection works
} else {
    echo "Connection failed."; // If something went wrong
}