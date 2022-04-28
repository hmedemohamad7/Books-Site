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


$sql = "create table comments (
				  id_comment int not null auto_increment,
				  id_book int not null,
				  id_user int not null,
				  text Text,
				  date VARCHAR(19) not null,
				  primary key (id_comment),
				  CONSTRAINT fk_comments_users FOREIGN KEY (id_user) REFERENCES users(id_user),
				  CONSTRAINT fk_comments_books FOREIGN KEY (id_book) REFERENCES books(id_book)
				  );" ;
			

if ($conn->query($sql)) {
	
	echo "table comments created successfully<br/>";
}
else{
	die( "Error during creating table comments<br/>" ); 
}

?>