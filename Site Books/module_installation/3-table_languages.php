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


$sql = "create table languages (
				  id_language INT not null auto_increment,
				  name_language VARCHAR(30) not null,
				  primary key (id_language),
				  unique (name_language)
				  );" ;

if ($conn->query($sql)) {
	
	echo "table languages created successfully<br/>";
}
else{
	die( "Error during creating table languages<br/>" ); 
}


$sql = "insert into languages(name_language)
				  values
			      ('English'),
				  ('French'),
				  ('Arabic'),
				  ('Chinese'),
				  ('Hindi'),
				  ('Portuguese'),
				  ('Russian'),
				  ('Japanese'),
				  ('German'),
				  ('Korean'),
				  ('Turkish'),
				  ('Urdu'),
				  ('Bengali'),
				  ('Spanish'),
				  ('Italian'),
				  ('Sweden'),
				  ('Polish'),
				  ('Norwegian'),
				  ('Indonesian'),
				  ('Dutch') ;" ;


if ($conn->query($sql))	{
	echo "insert in table languages done<br/>";
}
else{
	die( "Error during insert values into table languages<br/>" ); 
}


$conn->close();
?>