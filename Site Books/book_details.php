<!--LIGNE 181-->
<?php 
	session_start();
	require("fonction.php") ;
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;
	


if(isset($_GET['v'])){
	
	$_SESSION['book']['id_book'] = $_GET['v'];
	
	$stmt = $conn->prepare("SELECT * FROM books WHERE id_book = ?"); 
	$stmt->bind_param("i", $_GET['v']);
	$stmt->execute() ;
	$result = $stmt->get_result() ;
	$row = $result->fetch_assoc();
	
	if($result->num_rows === 0)
		die("<h1>book not found!!!</h1>");
	
	$_SESSION['book']['id_book'] = $row['id_book'] ;
	$_SESSION['book']['title'] = $row['title'];
	$_SESSION['book']['subtitle'] = $row['subtitle'];
	$_SESSION['book']['publisher'] = $row['publisher'];
	$_SESSION['book']['publisherlink'] = $row['publisherlink'];
	$_SESSION['book']['id_language'] = $row['id_language'];
	$_SESSION['book']['id_originlanguage'] = $row['id_originlanguage'];
	$_SESSION['book']['ebookISBN'] = $row['ebookISBN'];
	$_SESSION['book']['year_of_pub'] = $row['year_of_pub'];
	$_SESSION['book']['num_of_pages'] = $row['num_of_pages'];
	$_SESSION['book']['age_range'] = $row['age_range'];
	$_SESSION['book']['price'] = $row['price'];
	$_SESSION['book']['keywords'] = $row['keywords'];
	$_SESSION['book']['abstract'] = $row['abstract'];
	$_SESSION['book']['id_category'] = $row['id_category'];
	
	$stmt2 = $conn->prepare("SELECT * FROM comments WHERE id_book = ? order by date desc"); 
	$xxx = $_GET['v'] ;
	$stmt2->bind_param("i", $xxx);
	$stmt2->execute() ;
	$result2 = $stmt2->get_result() ;
//}


if(isset($_POST['s'])){
	$stmt11 = $conn->prepare("select id_user from users where username = ?");
	$abc123 = $_SESSION['login']['username'] ;
	$stmt11->bind_param("s",$abc123);
	$stmt11->execute() ;
	$result11 = $stmt11->get_result() ;
	$row11 = $result11->fetch_assoc() ;
	
	$stmt10 = $conn->prepare("insert into comments(id_book,id_user,text,date) values(?,?,?,?)");
	$a = $_GET['v'];
	$b = $row11['id_user'] ;
	$c = $_POST['ta'];
	$d = date("Y-m-d h:i:s");
	$stmt10->bind_param("iiss",$a,$b,$c,$d);
	$stmt10->execute() ;
	$result10 = $stmt10->get_result() ;
	
	header("Location: book_details.php?v=".$_GET['v']) ;
}

if(isset($_POST['s2'])){
	
	$stmt100 = $conn->prepare("select * from ratings where id_user = ? and id_book = ?") ;
	$nm = $_SESSION['login']['id_user'] ;
	$mm = $_GET['v'];
	$stmt100->bind_param("ii",$nm,$mm);
	$stmt100->execute() ;
	$result100 = $stmt100->get_result() ;
	$row100 = $result100->fetch_assoc() ;	

	
	if($result100->num_rows == 0){
		$stmt110 = $conn->prepare("insert into ratings(id_book,id_user,nb_stars) values(?,?,?)") ;
		$bbnm = $_GET['v'] ;
		$x11121 = $_SESSION['login']['id_user'];
		$y121 = $_POST['select1'] ;
		$stmt110->bind_param("iii",$bbnm,$x11121,$y121);
		$stmt110->execute() ;
		$result110 = $stmt110->get_result() ;
		//$row110 = $result110->fetch_assoc() ;
	}
	else{
		$stmt110 = $conn->prepare("update ratings set nb_stars = ? where id_user = ? and id_book = ?") ;
		$f11 = $_POST['select1'];
		$f22 = $_SESSION['login']['id_user'];
		$f33 = $_GET['v'] ;
		$stmt110->bind_param("iii",$f11,$f22 ,$f33);
		$stmt110->execute() ;
		$result110 = $stmt110->get_result() ;
		//$row110 = $result110->fetch_assoc() ;
	}
	header("Location: ".$_SERVER['PHP_SELF']."?v=".$_GET['v']);
}

if(isset($_POST['add_img1'])){
	add_an_image_to_a_book($conn,$_SESSION['book']['id_book'],$_SESSION['book']['title'],"filex");
	header("Location: book_details.php?v=".$_SESSION['book']['id_book']) ;
}

if(isset($_POST['d_authors'])){
	delete_author_by_id($conn, $_POST['h_authors']);
}

if(isset($_POST['d_book'])){
	delete_a_book_with_comments_authors_ratings($_POST['h_book'],$conn);
	delete_non_empty_directory("./images/".$row['title']);
	header("Location: home.php") ;
}


if(isset($_POST['d_cmt'])){
	delete_a_comment_with_sub_comments($_POST['h_cmt'],$conn);
	header("Location: book_details.php?v=".$_GET['v']) ;
}

if(isset($_POST['r_img'])){
	$path_1_1 = "./images/".$_SESSION['book']['title'] ;
		
		if (is_uploaded_file($_FILES['filey']['tmp_name']) && image_type($_FILES['filey']['type']) == true ){
				$type = explode('/',$_FILES['filey']['type']) ;					
				$path_2_2 = $_SESSION['book']['title']. "_" . $_POST['h_img'] . "." .$type[1] ;
				
				$path = "./images/".$_SESSION['book']['title'] ;
				$abc = scandir($path) ;
				unlink($path."/".$abc[$_POST['h_img']+1]) ;
				
				if (!move_uploaded_file($_FILES['filey']['tmp_name'], $path_1_1."/".$path_2_2))
				{
					header("Location: fail2.php");
					echo 'Problem: Could not move file to destination directory.';
					exit;
				}	
		}
		else{
			header("Location: fail1.php");
		}	
	header("Location: book_details.php?v=".$_SESSION['book']['id_book']) ;
}

if(isset($_POST['d_img'])){
	delete_an_image_for_a_book($conn,$_SESSION['book']['id_book'],$_SESSION['book']['title'],$_POST['h_img']) ;
	header("Location: book_details.php?v=".$_SESSION['book']['id_book']) ;	
}	

if(isset($_POST['d_sub_cmt'])){
	$stmt_sc = $conn->prepare("delete from sub_comments where id_sub_comment = ?") ;
	$rrr = $_POST['h_sub_cmt'] ;
	$stmt_sc->bind_param("i",$rrr) ;
	$stmt_sc->execute() ;
	header("Location: book_details.php?v=".$_GET['v']) ;
}
	
if(isset($_POST['login'])){
	
	$res_fct = verify_login_database($_POST['username'],$_POST['password'],$conn) ;
	
	if(is_bool($res_fct))
		header('Location: signin.php?a=1')  ;
	
	else {
		$_SESSION['login']['id_user'] = $res_fct['id_user'] ;
		$_SESSION['login']['username'] = $res_fct['username'] ;
		$_SESSION['login']['is_activated'] = $res_fct['is_activated'] ;
		$_SESSION['login']['is_banned'] = $res_fct['is_banned'] ;
		$_SESSION['login']['is_admin'] = $res_fct['is_admin'] ;
		header('Location: home.php')  ;
	}
}


if(isset($_POST['signup'])){
	header('Location: signup.php');
}


if(isset($_POST['logout'])){
	session_destroy();
	header('Location: home.php') ;
}

?>


<!DOCTYPE html>
<html>


<title>W3.CSS Template</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-black.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif;}
.w3-sidebar {
  z-index: 3;
  width: 250px;
  top: 43px;
  bottom: 0;
  height: inherit;
}
</style>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-theme w3-top w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
	
	<a href="home.php" class="w3-bar-item w3-button w3-theme-l1">Home</a>
	
	<?php
		if(!isset($_SESSION['login'])){
	?>
	<form method=post action=signup.php>
	<input type=submit name=signup value="Sign up" class="w3-bar-item w3-button w3-theme-l1"/>
	</form>
	<?php
		}
	?>
	
	
	<?php
	for($i=1;$i<=14;$i++)
		echo '<a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">       </a>';
	?>
	
	<?php
		if(!isset($_SESSION['login'])) {
	?>
	
	<form method=post action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v'];?>">
	username:
	<input type=text name=username />
	password:
	<input type=password name=password /> 
	<input type=submit name=login value=login /> 
	</form>
	
	<?php
		}
		
		elseif(isset($_SESSION['login'])){
			for($i=1;$i<=23;$i++){
				echo '<a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">       </a>';
			}
	?>
	
	<form method=post action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v'];?>">
	<?php echo $_SESSION['login']['username']; ?>
	<input type=submit name=logout value=logout />
	</form>
	
	<?php
			
		}
		
	?>
	
  </div>
</div>

<!-- Sidebar -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hover-black w3-hide-large" title="Close Menu">
    <i class="fa fa-remove"></i>
  </a>
  
  <h4 class="w3-bar-item"><b>Menu</b></h4>
  
  <?php
	include("admin_options.php");
  ?>
  
  <!--<a class="w3-bar-item w3-button w3-hover-black" href="#">Link</a>-->
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">




  
<?php
	
	
	
	
	
		//$stmt2 = $conn->prepare(/*JOINTURE POUR OBTENIR NAME AUTHOR'S*/) ;
?>
		<div class="w3-row w3-padding-64">
		<div class="w3-twothird w3-container">
		  <h1 class="w3-text-teal"><?php echo $row['title']; ?></h1> 
		  
		  <?php
		    if(isset($_SESSION['login']) && isset($_SESSION['login'])){
				$stmt_ur = $conn->prepare("select nb_stars from ratings where id_book = ? and id_user = ? ") ;
				$t11 = $_GET['v'] ;
				$t12 = $_SESSION['login']['id_user'] ;
				$stmt_ur->bind_param("ii",$t11,$t12);
				$stmt_ur->execute() ;
				$result_ur = $stmt_ur->get_result() ;
				$row_ur = $result_ur->fetch_assoc();
				
				$stmt_ar = $conn->prepare("select count(*) co, sum(nb_stars) sn from ratings where id_book = ?") ;
				$yyt = $_GET['v'] ;
				$stmt_ar->bind_param("i",$yyt);
				$stmt_ar->execute() ;
				$result_ar = $stmt_ar->get_result() ;
				$row_ar = $result_ar->fetch_assoc();
				
				if($result_ur->num_rows === 0)
					$user_rating = 0 ;
				else
					$user_rating = $row_ur['nb_stars'] ;
				
				if($result_ar->num_rows === 0 || $row_ar['co'] === 0)
					$average_rating = 0 ;
				else
					$average_rating = $row_ar['sn'] / $row_ar['co'] ;
			//average rating
			}
		  
			$stmt3 = $conn->prepare("select name_language from languages where id_language = ?") ;
			$xca = $row['id_language'] ;
			$stmt3->bind_param("i",$xca);
			$stmt3->execute() ;
			$result = $stmt3->get_result() ;
			$row3 = $result->fetch_assoc();
			
			$xca = $row['id_originlanguage'] ;
			$stmt4 = $conn->prepare("select name_language from languages where 	id_language = ?") ;
			$stmt4->bind_param("i",$xca);
			$stmt4->execute() ;
			$result = $stmt4->get_result() ;
			$row4 = $result->fetch_assoc();
			
			$xca = $row['id_category'] ;
			$stmtx = $conn->prepare("select name_category from categories  where id_category = ?") ;
			$stmtx->bind_param("i",$xca);
			$stmtx->execute() ;
			$result = $stmtx->get_result() ;
			$rowx = $result->fetch_assoc();
		  ?>
		  <p>
			 subtitle: <?php echo $row['subtitle']; ?><br/>
		     language: <?php echo $row3['name_language'];  ?><br/>
			 origin language: <?php echo $row4['name_language'];  ?><br/>
			 publisher: <?php echo $row['publisher'] ; ?><br/>
			 publisher link:<a href="<?php echo "publisher_link.php?link=".$row['publisherlink']; ?>"> <?php echo $row['publisherlink'] ; ?> </a><br/>
			 ebook ISBN: <?php echo $row['ebookISBN'] ; ?><br/>
			 year of publication: <?php echo $row['year_of_pub'] ; ?><br/>
			 number of pages: <?php echo $row['num_of_pages'] ; ?><br/>
			 Age range: <?php echo $row['age_range'] ; ?><br/>
			 keywords: <?php echo $row['keywords'] ; ?><br/>
			 price: <?php echo $row['price'] ; ?> $<br/>
			 category: <?php echo $rowx['name_category'] ; ?><br/><br/>
			 <u>abstract:</u><br/><?php echo $row['abstract'] ; ?><br/><br/>
			 <?php
				$stmtxy = $conn->prepare("select * from authors where id_book = ?") ;
				$a = $_GET['v'];
				$stmtxy->bind_param("i",$a);
				$stmtxy->execute() ;
				$resultxy = $stmtxy->get_result();
				$i=1 ;
				while($rowxy = $resultxy->fetch_assoc()){
					echo "<u>Author ".$i." :</u>" ;
					echo "<br/>" ;
					$i++;
	
					echo "title: ".$rowxy['title']."<br/>" ;
					echo "first name: ".$rowxy['firstname_author']."<br/>" ;
					echo "last name: ".$rowxy['lastname_author']."<br/>" ;
					
						$page = $_SERVER['PHP_SELF']."?v=".$_GET['v'] ;
						
						if(isset($_SESSION['login'])){
							if(his_book($conn,$_SESSION['book']['id_book'],$_SESSION['login']['id_user']) && $_SESSION['login']['is_activated'] == true && $_SESSION['login']['is_banned']==false){
								if($resultxy->num_rows>1){
									echo "<form method=post action=$page style='display: inline;'>";
									echo "<input type=submit name=d_authors value=x />";
									echo "<input type=hidden name=h_authors value=".$rowxy['id_author']." />" ;
									echo "</form>";
								}
								echo "<form method=post action=edit_author.php style='display: inline;'>";
								echo "<input type=submit name=e_authors value=edit />";
								echo "<input type=hidden name=h_authors value=".$rowxy['id_author']." />" ;
								echo "</form>";
							}
						}
					
					echo "<br/><br/><br/><br/>";
				}
				
				if(isset($_SESSION['login'])){
					if(his_book($conn,$_SESSION['book']['id_book'],$_SESSION['login']['id_user']) && $_SESSION['login']['is_activated'] == true && $_SESSION['login']['is_banned']==false){
						?>
						<form method=post action=add_author.php />
						<input type=submit name=add_author value="Add author" />
						</form>
						<?php
					}
				}
			 ?>
		  </p>
		  
		</div>
		
		<br/><br/><br/>
		
		<div class="w3-third w3-container">
		  <!--<p class="w3-border w3-padding-large w3-padding-32 w3-center">Book's Photo1</p>-->
		  <?php
			$stmt_1111 = $conn->prepare("select nb_images from books where id_book = ?");
			$stmt_1111->bind_param("i",$_GET['v']);
			$stmt_1111->execute();
			$result_1111 = $stmt_1111->get_result();
			$row_1111 = $result_1111->fetch_assoc();
		  ?>
		  <p>
		  <?php
		  
			for($i=1; $i<=$row_1111['nb_images']; $i++) {
				$path = "./images/".$_SESSION['book']['title'] ;
				$ab100 = scandir($path) ;
				
		  ?>
				<img src="./images/<?php echo $_SESSION['book']['title'].'/'.$ab100[$i+1]; ?>" width=200 height=200 />
				<br/>
		  <?php
		  
				if(isset($_SESSION['login']) && $_SESSION['login']['is_banned']==false){
					if(his_book($conn,$_SESSION['book']['id_book'],$_SESSION['login']['id_user'])){
						echo "<form method=post action=".$_SERVER['PHP_SELF']."?v=".$_GET['v']." enctype='multipart/form-data' style='display: inline;' />" ;
						echo "<input type=hidden name=h_img value=".$i." />" ;
						echo "<input type=submit name=r_img value=Replace style='display: inline;'/>";
						echo "<input type=file name=filey  required />" ;
						echo "</form>";
						 if($i>1){
							echo "<form method=post action=".$_SERVER['PHP_SELF']."?v=".$_GET['v']." />" ;
							echo "<input type=hidden name=h_img value=".$i." />" ;
							echo "<input type=submit name=d_img value=x style='display: inline;'/>";
							echo "</form>" ;
						 }
					}
				}
				echo "<br/>-----------------------------------------------------------------";
				echo "<br/><br/><br/><br/><br/>";
			}
			
			if(isset($_SESSION['login']) && $_SESSION['login']['is_banned']==false){
				if(his_book($conn,$_SESSION['book']['id_book'],$_SESSION['login']['id_user']) && $row_1111['nb_images'] < 5){
					$path = "book_details.php?v=".$_SESSION['book']['id_book'] ;
					echo "<form method=post action=$path enctype='multipart/form-data' style='display: inline;' />" ;
					echo '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />' ;
					echo "<input type=submit name=add_img1 value='Add Image'  />" ;
					echo "<input type=file name=filex  required />" ;
					echo "</form>";
				}
			}
			
		  ?>
		  </p>
		  
		  
		  <?php
		  if(isset($_SESSION['login'])){
			  if( isset($_SESSION['login']['is_activated']) && isset($_SESSION['login']['is_banned'])){
				if($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false){
		  ?>
		  <form method=post action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v']; ?>" />
		  <select name=select1>
		  <option value=1>1star - didn’t like it</option>
		  <option value=2>2star - it was okay</option>
		  <option value=3>3star - liked it</option>
		  <option value=4>4star - really liked it</option>
		  <option value=5>5star - it was awesome</option> 
		  </select>
		  
		  <input type=submit name=s2 value=rate /><br/><br/>
		  <font color=red>Your current rate:</font> <?php echo $user_rating ; ?><br/><br/>
		  <font color=red>Average rate:</font> <?php echo $average_rating ; ?><br/><br/>
		  </form>
		  <?php
					}
			  }
		  }
		  ?>
		  
		  
		  <?php
				if(isset($_SESSION['login'])){
					if(	isset($_SESSION['login']['is_admin']) && isset($_SESSION['login']['id_user']) && $_SESSION['login']['is_banned']==false) {
						if($_SESSION['login']['is_admin'] == true || his_book($conn,$_GET['v'],$_SESSION['login']['id_user'])){
		  ?>
				 <form method=post action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v']; ?>" />
				 <input type=submit name=d_book value="delete the book !" />
				 <input type=hidden name=h_book value="<?php echo $row['id_book']; ?>" />
				 </form>
				 <?php
					if(his_book($conn,$_GET['v'],$_SESSION['login']['id_user'])){
				 ?>
						<form method=post action="edit_book.php" />
						<input type=submit name=e_book value="edit" />
						<input type=hidden name=h123 value="<?php echo $_GET['v']; ?>" />
						</form>
				 <?php
							}
						}
					}
				}
		  ?>
		</div>
		
		
	  </div>
	  <br/><br/><br/>
	  
	  
	  <b><font size=6><?php echo "&nbsp;" ; ?>Comments</font></b>
	  <br/><br/><br/>
	  <?php
		while($row2 = $result2->fetch_assoc()){
			$stmt5 = $conn->prepare("select username from users where id_user = ?") ;
			$stmt5->bind_param("i",$row2['id_user']);
			$stmt5->execute() ;
			$result5 = $stmt5->get_result() ;
			$row5 = $result5->fetch_assoc();
			
			$_SESSION['comment']['id_comment'] = $row2['id_comment'] ;
			
			if(isset($_SESSION['login'])){
				if(isset($_SESSION['login']['is_admin'])) {
					if($_SESSION['login']['is_admin'] == 1){
					echo "<b><font color=red>id user: " . $row2['id_user'] . " , id cmt: ". $_SESSION['comment']['id_comment'] . "</font></b><br/>" ;
					echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ;
			}}}
		  
			echo "<u>" . $row5['username']  ; 
			echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" ;
			echo $row2['date'] ."&nbsp;". ":" . "</u>" ;
			echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" ;
	  ?>
	  
	  <?php
		if(isset($_SESSION['login'])){
			if(isset($_SESSION['login']['is_activated']) && isset($_SESSION['login']['is_admin']) && isset($_SESSION['login']['is_banned'])) {
				if($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_admin']==false && $_SESSION['login']['is_banned']==false){
	  ?>
			<a href = "write_report.php?r=c"><font color=blue>report</font></a>

	  <?php
				}
			}
		}
	  ?>
	  
	  <?php
		if(isset($_SESSION['login'])){
			if(isset($_SESSION['login']['is_activated']) && isset($_SESSION['login']['is_banned'])) {
				if($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false){
	  ?>
			<form method=post action="<?php echo "reply_comment.php" ; ?>" style="display: inline;" />
			<input type=hidden name=h_cmttt value="<?php echo $row2['id_comment']; ?>" />
			<input type=submit name=reply value=reply />
			</form>

	  <?php
				}
			}
		}
	  ?>
			
	    <?php
			echo "<br/>";
			echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ;
			echo $row2['text'] ;
			echo "<br/><br/><br/>";
		?>
		<center>
		<?php
			$stmt_x1x = $conn->prepare("select * from sub_comments where id_sup_comment = ? order by date desc");
			$ax = $row2['id_comment'] ;
			$stmt_x1x->bind_param("i",$ax) ;
			$stmt_x1x->execute() ;
			$result_x1x = $stmt_x1x->get_result() ;
			while($row_x1x = $result_x1x->fetch_assoc()){
				if(isset($_SESSION['login'])){
					if(isset($_SESSION['login']['is_admin'])) {
						if($_SESSION['login']['is_admin'] == true){
							echo "<b><font color=red>id user: " . $row_x1x['id_user']  . "</font></b><br/>" ;
						}
					}
				}
				$stmt_mn = $conn->prepare("select * from users where id_user = ?") ;
				$xa = $row_x1x['id_user'] ;
				$stmt_mn->bind_param("i",$xa);
				$stmt_mn->execute() ;
				$result_mn = $stmt_mn->get_result() ;
				$row_x1x111 = $result_mn->fetch_assoc() ;
				echo "<u>" . $row_x1x111['username']  ; 
				echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" ;
				echo $row_x1x['date'] ."&nbsp;". ":" . "</u>" ;
				echo "<br/>" ;
				echo "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;" ;
				echo $row_x1x['text']."<br/><br/>";
				if(isset($_SESSION['login'])){
					if(isset($_SESSION['login']['is_admin'])) {
						if($_SESSION['login']['is_admin'] == true){
				?>
							<form action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v']; ?>" method=post />
							<?php echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ?>
							<input type=submit name=d_sub_cmt value="delete the sub comment" />
							<input type=hidden name=h_sub_cmt value="<?php echo $row_x1x['id_sub_comment']; ?>" />
							</form>
				<?php
						}
					}
				}
				echo "<br/><br/>" ;
			}
		?>
		</center>
		<?php
				if(isset($_SESSION['login'])){
					if(isset($_SESSION['login']['is_admin'])) {
						if($_SESSION['login']['is_admin'] == true){

		?>
			<form action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v']; ?>" method=post />
			<?php echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ?>
			<input type=submit name=d_cmt value="delete the comment" />
			<input type=hidden name=h_cmt value="<?php echo $row2['id_comment']; ?>" />
			</form>
		<?php
				}
			}	
			echo "<br/><br/><br/>";
		}
		}//while
	
		echo "<br/><br/><br/>" ;
	
		if(isset($_SESSION['login'])){
			if( isset($_SESSION['login']['is_activated']) && isset($_SESSION['login']['is_banned']) ) {
				if($_SESSION['login']['is_activated'] == true && $_SESSION['login']['is_banned'] == false){
		?>
				<form method=post action="<?php echo $_SERVER['PHP_SELF']."?v=".$_GET['v']; ?>">
				<?php echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ?><textarea name=ta width=30 height=60></textarea>
				<br/>
				<?php echo "&nbsp;" ; echo "&nbsp;" ; echo "&nbsp;" ?><input type=submit name=s value=add />
				<br/><br/>
				</form>
		<?php
				}
			}
		}
		?>
	  
<?php
//-----------------------------------------------------------------print_all_books--------------------------------------------------------
?>



    <!--<div class="w3-container w3-theme-l1">
      <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
    </div>
  </footer>-->

<!-- END MAIN -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}
</script>







</body>
</html>


<?php
	$conn->close();
}
?>
