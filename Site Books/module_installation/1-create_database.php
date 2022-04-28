<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully<br/>";




if ($conn->query("create database PROJET_PHP_V3_try")) {
	
	echo "Database created successfully";
}
else{
	echo "Error during creating database"; 
}


$conn->close();
?>