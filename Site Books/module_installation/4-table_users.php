<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "PROJET_PHP_V3_try" ;

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully<br/>";


$sql = "create table users (
				  id_user int not null auto_increment,
				  first_name varchar(30) not null,
				  last_name varchar(30) not null,
				  username varchar(30) not null,
				  password varchar(100) not null,
				  email varchar(50) not null,
				  country varchar(30) not null,
				  date_of_birth varchar(10) not null ,
				  occupation varchar(30) not null,
				  hobbies varchar(30) not null,
				  is_activated boolean not null,
				  is_admin boolean not null,
				  is_banned boolean not null,
				  primary key (id_user),
				  unique (username , email)
				  );" ;

if ($conn->query($sql)) {
	
	echo "table users created successfully<br/>";
}
else{
	die( "Error during creating table users<br/>" ); 
}