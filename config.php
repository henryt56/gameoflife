<?php
// config.php - Database configuration
$db_host = 'localhost';
$db_user = 'htruong17';
$db_pass = 'htruong17'; 
$db_name = 'htruong17';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Use the database
$conn->select_db($db_name);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) !== TRUE) {
    die("Error creating users table: " . $conn->error);
}

// Create game_sessions table
$sql = "CREATE TABLE IF NOT EXISTS game_sessions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_time TIMESTAMP NULL,
    generations INT(11) DEFAULT 0,
    max_population INT(11) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if ($conn->query($sql) !== TRUE) {
    die("Error creating game_sessions table: " . $conn->error);
}

// Create admin user if it doesn't exist
$admin_user = 'admin';
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $admin_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, password, is_admin) VALUES (?, ?, 1)");
    $stmt->bind_param("ss", $admin_user, $admin_pass);
    $stmt->execute();
}

return $conn;
?>