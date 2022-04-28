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


$sql = "create table categories (
				  id_category INT not null auto_increment,
				  name_category VARCHAR(30) not null,
				  EEE INT not null,
				  primary key (id_category),
				  unique (name_category)
				  );" ;

if ($conn->query($sql)) {
	
	echo "table categories created successfully<br/>";
}
else{
	die( "Error during creating table languages<br/>" ); 
}


$sql = "insert into categories(name_category,EEE)
				  values
			      ('Arts & Music',1),
				  ('Biographies',1),
				  ('Business',1),
				  ('Kids',1),
				  ('Comics',1),
				  ('Computers & Tech',1),
				  ('Cooking',1),
				  ('Hobbies & Crafts',1),
				  ('Edu & Reference',1),
				  ('Poetry',1),
				  ('Health & Fitness',1),
				  ('History',1),
				  ('Home & Garden',1),
				  ('Horror',1),
				  ('Entertainment',1),
				  ('Literature',1),
				  ('Medical',1),
				  ('Mysteries',1),
				  ('Parenting',1),
				  ('Social Sciences',1),
				  ('Religion',1),
				  ('Romance',1),
				  ('Science & Math',1),
				  ('Sci-Fi & Fantasy',1),
				  ('Self-Help',1),
				  ('Sports',1),
				  ('Travel',1),
				  ('True Crime',1);" ;


if ($conn->query($sql))	{
	
	echo "insert in table categories done<br/>";
}
else{
	die( "Error during insert values into table categories<br/>" ); 
}


$conn->close();

?>