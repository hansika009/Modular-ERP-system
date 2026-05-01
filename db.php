<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "modular_erp";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE TABLE IF NOT EXISTS USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)");

$admin_check = $conn->query("SELECT * FROM USERS WHERE username = 'admin'");
if ($admin_check->num_rows == 0) {
    $hashed_password = password_hash('password', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO USERS (username, password) VALUES ('admin', '$hashed_password')");
}

function check_auth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
?>
