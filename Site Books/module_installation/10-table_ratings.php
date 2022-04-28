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


$sql = "create table ratings (
				  id_line int not null auto_increment,
				  id_book int not null,
				  id_user int not null,
				  nb_stars int not null,
				  primary key (id_line),
				  CONSTRAINT fk_ratings_users FOREIGN KEY (id_user) REFERENCES users(id_user),
				  CONSTRAINT fk_ratings_books FOREIGN KEY (id_book) REFERENCES books(id_book)
				  );" ;
			

if ($conn->query($sql)) {
	
	echo "table ratings created successfully<br/>";
}
else{
	die( "Error during creating ratings ratings<br/>" ); 
}

?>