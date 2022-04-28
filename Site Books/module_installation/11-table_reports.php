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


$sql = "create table reports (
				  id_report int not null auto_increment,
				  id_user int not null,
				  id_comment int not null,
				  date VARCHAR(19) not null,
				  description VARCHAR(150) not null,
				  primary key (id_report),
				  CONSTRAINT fk_reports_users FOREIGN KEY (id_user) REFERENCES users(id_user),
				  CONSTRAINT fk_reports_comments FOREIGN KEY (id_comment) REFERENCES comments(id_comment)
				  );" ;
			

if ($conn->query($sql)) {
	
	echo "table reports created successfully<br/>";
}
else{
	die( "Error during creating table reports<br/>" ); 
}

?>