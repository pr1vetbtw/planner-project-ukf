<?php

session_start(); // Start session

session_destroy(); // Destroy session (logout user)

header("Location: login.php"); // Redirect to login
exit();