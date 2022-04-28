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

$crypted_password = crypt('Admin1234','...');

$sql = "insert into users(
				  first_name,
				  last_name,
				  username,
				  password,
				  email,
				  country,
				  date_of_birth,
				  occupation,
				  hobbies,
				  is_activated,
				  is_admin,
				  is_banned)
				  values
				  ('X','Y','Admin','$crypted_password','admin_projet_php@gmail.com','Lebanon','1980-12-24','Prof','Reading',true,true,false);" ;
				  

if ($conn->query($sql)) {
	
	echo "admin inserted in table users successfully<br/>";
}
else{
	die( "Error during inserting admin in table users<br/>" ); 
}