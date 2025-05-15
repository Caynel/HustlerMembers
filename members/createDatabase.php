<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";

// Connect without selecting a DB yet
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//Create database
$dbName = "HustleCoreDB";
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";

if ($conn->query($sql) === TRUE) {
    echo "<script>Database '$dbName' created successfully!</script>";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn->close();
?>