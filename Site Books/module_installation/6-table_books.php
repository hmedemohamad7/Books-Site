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


$sql = "create table books (
				  id_book int not null AUTO_INCREMENT,
				  title varchar(30) not null,
				  subtitle varchar(30),
				  publisher varchar(30) not null,
				  publisherlink varchar(30),
				  id_language int not null,
				  id_originlanguage int not null,
				  ebookISBN varchar(30) not null unique,
				  year_of_pub int not null,
				  num_of_pages int not null,
				  age_range varchar(20) not null,
				  keywords text not null,
				  price int not null,
				  abstract text not null,
				  id_category int not null,
				  nb_images int not null,
				  id_user int not null,
				  primary key (id_book),
				  unique (title),
				  CONSTRAINT fk_bookscategories_id FOREIGN KEY (id_category) REFERENCES categories(id_category),
				  CONSTRAINT fk_booksusers_id FOREIGN KEY (id_user) REFERENCES users(id_user),
				  CONSTRAINT fk_languages_id FOREIGN KEY (id_language) REFERENCES users(id_language),
				  CONSTRAINT fk_bookslanguages2_id FOREIGN KEY (id_originlanguage) REFERENCES users(id_language)
				  );" ;

if ($conn->query($sql)) {
	
	echo "table books created successfully<br/>";
}
else{
	die( "Error during creating table books<br/>" ); 
}