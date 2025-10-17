<?php
// config/database.php
// Database configuration file

// Define database constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change this to your DB user
define('DB_PASS', '');      // Change this to your DB password
define('DB_NAME', 'historical_recipes');  // Change this to your DB name

// Create a database connection
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Connection error: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
}
?>