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


$sql = "create table sub_comments (
				  id_sub_comment int not null auto_increment,
				  id_sup_comment int not null,
				  id_user int not null,
				  text Text,
				  date VARCHAR(19) not null,
				  primary key (id_sub_comment),
				  CONSTRAINT fk_subcomments_users FOREIGN KEY (id_user) REFERENCES users(id_user),
				  CONSTRAINT fk_subcomments_comments FOREIGN KEY (id_sup_comment) REFERENCES comments(id_sup_comment)
				  );" ;
			

if ($conn->query($sql)) {
	
	echo "table sub_comments created successfully<br/>";
}
else{
	die( "Error during creating table sub_comments<br/>" ); 
}

?>