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


$sql = "create table authors (
				  id_author INT not null auto_increment,
				  title VARCHAR(30) not null,
				  firstname_author VARCHAR(30) not null,
				  lastname_author VARCHAR(30) not null,
				  id_book int not null,
				  primary key (id_author),
				  CONSTRAINT fk_authors_books FOREIGN KEY (id_book) REFERENCES books(id_book)
				  );" ;

if ($conn->query($sql)) {
	
	echo "table authors created successfully<br/>";
}
else{
	die( "Error during creating table authors<br/>" ); 
}