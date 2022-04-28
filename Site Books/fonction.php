<?php

function verify_login_database( $username , $password , $conn ) {
	
	$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? and password = ?"); 
	
	$crypted_password = crypt($password,"...");
	
	$stmt->bind_param("ss", $username , $crypted_password); 

	$stmt->execute();
	
	$result = $stmt->get_result(); 
	
	 
	echo "<script>";
	echo "alert($result->num_rows);";
	echo "</script>" ;
	
	if($result->num_rows !== 1){
		$stmt->close();
		return false ;
	}
	
	if($result->num_rows === 1) {
		$row = $result->fetch_assoc();
		$stmt->close();
		return $row ;
	}		
}


function connect_to_database($servername, $username, $password, $my_database) {
	
	$conn = new mysqli($servername, $username, $password, $my_database);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	return $conn ;
}

					
function image_type($type){
	if($type == "image/png" ||
	   $type == "image/jpg" ||
	   $type == "image/jpeg"||
	   $type == "image/gif" ||
	   $type == "image/jfif")
	   return true ;
	   
	else
		return false ;
}


function get_id_by_name_language($name,$conn) {
	if($stmt = $conn->prepare("select id_language from languages where name_language = ?")){
		$stmt->bind_param("s",$name);
		$stmt->execute() ;
		$result = $stmt->get_result() ;
		$row = $result->fetch_assoc();
		$stmt->close();
		return $row['id_language'] ;
	}
}


function sign_up($conn,$firstname,$lastname,$username,$password,$email,$country,$d_of_b,$occupation,$hobbies){
	$crypted_password = crypt($password,"...");
	if($stmt = $conn->prepare("insert into 
										   users(first_name,last_name,username,password,email,
	                                             country,date_of_birth,occupation,hobbies,
												 is_activated,is_admin,is_banned)
										   values(?,?,?,?,?,?,?,?,?,?,?,?);")){
		$false_ = 0 ;
		$stmt->bind_param("sssssssssiii",$firstname,$lastname,$username,$crypted_password,$email,$country,$d_of_b,$occupation,$hobbies,$false_,$false_,$false_);
		$stmt->execute() ;
		return $stmt->num_rows ;
	}
}

function sign_up2($conn,$firstname,$lastname,$username,$password,$email,$country,$d_of_b,$occupation,$hobbies){
	$crypted_password = crypt($password,"...");
	if($stmt = $conn->prepare("insert into 
										   users(first_name,last_name,username,password,email,
	                                             country,date_of_birth,occupation,hobbies,
												 is_activated,is_admin,is_banned)
										   values(?,?,?,?,?,?,?,?,?,?,?,?);")){
		$false_ = 0 ;
		$true_ = 1 ;
		$stmt->bind_param("sssssssssiii",$firstname,$lastname,$username,$crypted_password,$email,$country,$d_of_b,$occupation,$hobbies,$true_,$false_,$false_);
		$stmt->execute() ;
		return $stmt->num_rows ;
	}
}


function add_a_book($conn,$title,$subtitle,$publisher,$publisherlink,$id_lang,$id_olang,
                    $ebookISBN,$y_of_p,$num_of_p,$age_range,$keywords,$price,$abstract,$id_category,$nb_images,$id_user){
						
	if($stmt = $conn->prepare("insert into books(title,subtitle,publisher,publisherlink,
	                           id_language,id_originlanguage,ebookISBN,year_of_pub,
	                           num_of_pages,age_range,keywords,price,abstract,id_category,nb_images,id_user) 
								values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")) {
								
		if(!$stmt->bind_param("ssssiisiissisiii",$title,$subtitle,$publisher,$publisherlink,
		$id_lang,$id_olang,$ebookISBN,$y_of_p,
		$num_of_p,$age_range,$keywords,$price,$abstract,$id_category,$nb_images,$id_user)){
			echo $stmt->errno . "..." . $stmt->error . "<br/>" ;
		}
		
		if(!$stmt->execute()){
			echo $stmt->errno . "..." . $stmt->error . "<br/>" ;
		}
		
		if($stmt->affected_rows === 1){
			/*$stmt2 = $conn->prepare("select * from books where title = ?  and subtitle = ?  and publisher = ?  and publisherlink = ?  and 
	                           id_language = ? and id_originlanguage = ?  and ebookISBN = ?  and year_of_pub = ?  and 
	                           num_of_pages = ?  and age_range = ?  and keywords = ?  and price = ?  and abstract = ?  and id_category = ? )");
			$stmt2->bind_param("ssssiisiissisi",$title,$subtitle,$publisher,$publisherlink,
												$id_lang,$id_olang,$ebookISBN,$y_of_p,
												$num_of_p,$age_range,$keywords,$price,$abstract,$id_category);*/
												
			$stmt2 = $conn->prepare("select * from books where title = ?");
			$stmt2->bind_param("s",$title);
												
			$stmt2->execute() ;
			$result2 = $stmt2->get_result() ;
			$row2 = $result2->fetch_assoc();
			$stmt->close();
			$stmt2->close();
			return $row2['id_book'] ;
		}
		else {
			$stmt->close();
			return false ;
		}
	}					
}


function add_author($conn,$title,$first,$last,$id_book){
	$stmt = $conn->prepare("insert into authors(title,firstname_author,lastname_author,id_book) values(?,?,?,?)");
	$stmt->bind_param("sssi",$title,$first,$last,$id_book);
	$stmt->execute() ;
	
	$stmt->close();
}



function his_book($conn,$id_book,$id_user){
	$stmt = $conn->prepare("select id_user from books where id_book = ?") ;
	$stmt->bind_param("i",$id_book) ;
	$stmt->execute() ;
	$result = $stmt->get_result() ;
	$row = $result->fetch_assoc() ;
	if($row['id_user'] == $id_user)
		return true ;
	return false ;
}


function create_array_books_average($conn){
	$stmt = $conn->query("SELECT * FROM books");
	$i=0 ;
	$my_array = "" ;
	while($row = $stmt->fetch_assoc()){
		$stmt_ar = $conn->prepare("select count(*) co, sum(nb_stars) sn from ratings where id_book = ?") ;
		$yyt = $row['id_book'] ;
		$stmt_ar->bind_param("i",$yyt);
		$stmt_ar->execute() ;
		$result_ar = $stmt_ar->get_result() ;
		$row_ar = $result_ar->fetch_assoc();
					
		if($result_ar->num_rows === 0 || $row_ar['co'] === 0)
			$average_rating = 0 ;
		else
			$average_rating = $row_ar['sn'] / $row_ar['co'] ;

		$my_array[$i] = $row ;
		$my_array[$i+1] = $average_rating ;
		
		$i = $i+2 ;
	}
	return $my_array;
}


function create_array_books_comment($conn){
	$stmt = $conn->query("SELECT * FROM books");
	$i=0 ;
	$my_array = "" ;
	while($row = $stmt->fetch_assoc()){
		$stmt_cmt = $conn->prepare("select count(*) co from comments where id_book = ?") ;
		$yyt = $row['id_book'] ;
		$stmt_cmt->bind_param("i",$yyt);
		$stmt_cmt->execute() ;
		$result_cmt = $stmt_cmt->get_result() ;
		$row_cmt = $result_cmt->fetch_assoc();
					
		if($result_cmt->num_rows === 0 || $row_cmt['co'] === 0)
			$nb_cmt = 0 ;
		else
			$nb_cmt = $row_cmt['co'] ;

		$my_array[$i] = $row ;
		$my_array[$i+1] = $nb_cmt;
		
		$i = $i+2 ;
	}
	return $my_array;
}


function create_array_users_books($conn){
	$stmt = $conn->query("SELECT * FROM users");
	$i=0 ;
	while($row = $stmt->fetch_assoc()){
		$stmt_cmt = $conn->prepare("select count(*) co from books where id_user = ?") ;
		$yyt = $row['id_user'] ;
		$stmt_cmt->bind_param("i",$yyt);
		$stmt_cmt->execute() ;
		$result_cmt = $stmt_cmt->get_result() ;
		$row_cmt = $result_cmt->fetch_assoc();
					
		if($result_cmt->num_rows === 0 || $row_cmt['co'] === 0)
			$nb_cmt = 0 ;
		else
			$nb_cmt = $row_cmt['co'] ;

		$my_array[$i] = $row ;
		$my_array[$i+1] = $nb_cmt;
		
		$i = $i+2 ;
	}
	return $my_array;
}


function print_top10_users($array_top10_users,$expected_len){
	for($i=0; $i<$expected_len-1; $i+=2){
?>
		<div class="w3-row w3-padding-64">
			<div class="w3-twothird w3-container">
				 <h1 class="w3-text-teal"><?php echo $array_top10_users[$i]['username']; ?></h1>
			   <p>id: <?php echo $array_top10_users[$i]['id_user']; ?><br/>Number of Books: <?php echo $array_top10_users[$i+1]; ?><br/></p>
			</div>
			
		</div>
<?php
	}
}


function update_the_book($conn,$id,$title,$subtitle,$ebookISBN,$y_of_p,$age1,$age2,$keywords,$price,$id_lang,$id_olang,$pub,$publink,$cat,$abs){
	$stmt = $conn->prepare("update books 
							set title = ? ,
							subtitle = ? ,
							ebookISBN = ? ,
							year_of_pub = ? ,
							age_range = ? ,
							price = ? ,
							abstract = ? ,
							keywords = ? ,
							publisher = ? ,
							publisherlink = ? ,
							id_category = ? ,
							id_language = ? ,
							id_originlanguage = ?
							where id_book = ?");
							$age_range = $age1."-".$age2;
	$stmt->bind_param("sssisissssiiii",$title,$subtitle,$ebookISBN,$y_of_p,$age_range,$price,$abs,$keywords,$pub,$publink,$cat,$id_lang,$id_olang,$id);
	$stmt->execute();
}

function sort_array_by_option($the_array){
	for($i=0 ; $i<sizeof($the_array)-1 ; $i=$i+2){
		for($j=$i+2 ; $j<sizeof($the_array) ; $j=$j+2){
			if($the_array[$j+1] > $the_array[$i+1]){
				$tmp = $the_array[$i+1];
				$the_array[$i+1] = $the_array[$j+1];
				$the_array[$j+1] = $tmp ;
				
				$tmp = $the_array[$i];
				$the_array[$i] = $the_array[$j];
				$the_array[$j] = $tmp ;
			}
		}
	}
	return $the_array ;
}

function delete_non_empty_directory($path){
	
	$abc = scandir($path) ;

	for($i=0 ; $i<sizeof($abc) ; $i++)
		if($abc[$i] !== "." && $abc[$i] !== "..")
			unlink($path."/".$abc[$i]) ;
		
	rmdir($path) ;
}


function delete_a_comment_with_sub_comments($id_comment,$conn){
	$stmt20 = $conn->prepare("delete from sub_comments where id_sup_comment = ?") ;
	$rrr = $id_comment ;
	$stmt20->bind_param("i",$rrr);
	$stmt20->execute() ;
	
	$stmt20 = $conn->prepare("delete from comments where id_comment = ?") ;
	$rrr = $id_comment ;
	$stmt20->bind_param("i",$rrr);
	$stmt20->execute() ;
}


function delete_a_book_with_comments_authors_ratings($id_book,$conn){
	$stmt_m = $conn->prepare("delete from ratings where id_book = ?") ;
	$po123 = $id_book ;
	$stmt_m->bind_param("i",$po123);
	$stmt_m->execute();
	
	$stmt778x = $conn->prepare("delete from authors where id_book = ?") ;
	$stmt778x->bind_param("i",$id_book);
	$stmt778x->execute() ;

	
	$stmt20 = $conn->prepare("delete from books where id_book = ?") ;
	$po123 = $id_book ;
	$stmt20->bind_param("i",$po123);
	$stmt20->execute() ;
	
	$stmt_n = $conn->prepare("select id_comment from comments where id_book = ?");
	$po123 = $id_book ;
	$stmt_n->bind_param("i",$po123);
	$stmt_n->execute();
	$result_n = $stmt_n->get_result();
	while($row_n = $result_n->fetch_assoc()){
		delete_a_comment_with_sub_comments($row_n['id_comment'],$conn);
	}
}


function check_if_username_exists($conn,$username){
	$bool = false;
	$stmt = $conn->prepare("select * from users where username = ?");
	$stmt->bind_param("s",$username);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows>0)
		$bool = true ;
	return $bool ;
}


function check_if_book_exists($conn, $title){
	$bool = false ;
	$stmt = $conn->prepare("select * from books where title = ?");
	$stmt->bind_param("s",$title) ;
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows>0){
		$row = $result->fetch_assoc() ;
		$bool = $row['id_book'];
	}
	return $bool ;
}


function delete_author_by_id($conn, $id_author){
	$stmt = $conn->prepare("delete from authors where id_author = ?");
	$x = $id_author ;
	$stmt->bind_param("i",$x);
	$stmt->execute() ;
}


function update_author_by_id($conn,$id,$title,$first,$last){
	$stmt = $conn->prepare("update authors set title = ? , firstname_author = ? , lastname_author = ? where id_author = ?") ;
	$stmt->bind_param("sssi",$title,$first,$last,$id);
	$stmt->execute();
}


function delete_an_image_for_a_book($conn,$id_book,$title_book,$image_nb){
	
	$path = "./images/".$title_book ;
	$abc = scandir($path) ;
	unlink($path."/".$abc[$image_nb+1]) ;
	
	for($i=($image_nb+1)+1 ; $i<sizeof($abc) ; $i++){
		$full_name = $abc[$i] ;
		$tab = explode("_",$full_name) ;
		$name = $tab[0];
		$tab2 = explode(".",$tab[1]) ;
		$number = $tab2[0] ;
		$extension = $tab2[1] ;
		$new_number = $number - 1 ;
		rename($path."/".$abc[$i],$path."/".$name."_".$new_number.".".$extension) ;
	}
		
	$stmt = $conn->prepare("update books set nb_images = nb_images-1 where id_book = ?");
	$stmt->bind_param("i",$id_book);
	$stmt->execute() ;
}


function add_an_image_to_a_book($conn,$id_book,$title_book,$file_name){
	$stmt = $conn->prepare("select nb_images from books where id_book = ?");
	$stmt->bind_param("i",$id_book);
	$stmt->execute() ;
	$result = $stmt->get_result();
	$row = $result->fetch_assoc() ;
	
	$path_1_1 = "./images/".$title_book ;
	if (is_uploaded_file($_FILES[$file_name]['tmp_name']) && image_type($_FILES[$file_name]['type']) == true ){
			$type = explode('/',$_FILES[$file_name]['type']) ;					
			$path_2_2 = $title_book. "_" . ($row['nb_images']+1) . "." .$type[1] ;
			if (!move_uploaded_file($_FILES[$file_name]['tmp_name'], $path_1_1."/".$path_2_2)){
				header("Location: fail2.php");
				echo 'Problem: Could not move file to destination directory.';
				exit;
			}	
	}
	else{
		header("Location: fail1.php");
	}
	
	$stmt = $conn->prepare("update books set nb_images = nb_images+1 where id_book = ?");
	$stmt->bind_param("i",$id_book);
	$stmt->execute() ;
}
?>