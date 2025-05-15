<?php
// session_start();

$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "HustleCoreDB";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>