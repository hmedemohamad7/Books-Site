
<?php 
	session_start();
	require("fonction.php") ;

if(isset($_SESSION['login'])){
		if($_SESSION['login']['is_admin'] == true || ($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false) && $_SESSION['login']['is_admin']==false){
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;


	
	if(isset($_POST['sss'])){
		
		
		if(!preg_match("#^[a-zA-Z ]+$#",$_POST['title']))
			die("<h1>invalid title</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[a-zA-Z ]+$#",$_POST['subtitle']))
			die("<h1>invalid subtitle</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[a-zA-Z]+$#",$_POST['publisher']))
			die("<h1>invalid publisher</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^www[.][a-z0-9][a-z0-9-._]*[a-z0-9]{1,}[.][a-z]{2,}$#",$_POST['pulisher_link']))
			die("<h1>invalid publisher link</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[0-9]+([0-9]*(-)?)+[0-9]+$$#",$_POST['isbn']))
			die("<h1>invalid eBook isbn</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[a-zA-Z0-9_.]+$#",$_POST['keywords']))
			die("<h1>invalid keywords</h1><a href=home.php>home</a>") ;	  	  
		
		
		
		$_SESSION['info_book']['title'] = $_POST['title'] ;
		
		$bool = check_if_book_exists($conn,$_POST['title']) ;
		if($bool == true)
			header("Location: add_book.php?e=1");
		
		$_SESSION['info_book']['subtitle'] = $_POST['subtitle'] ;
		$_SESSION['info_book']['publisher'] = $_POST['publisher'] ;
		$_SESSION['info_book']['pulisher_link'] = $_POST['pulisher_link'] ;
		$_SESSION['info_book']['id_language'] = get_id_by_name_language($_POST['language'],$conn) ;
		$_SESSION['info_book']['id_olanguage'] = get_id_by_name_language($_POST['olanguage'],$conn) ;
		$_SESSION['info_book']['isbn'] = $_POST['isbn'] ;
		$_SESSION['info_book']['yofp'] = $_POST['yofp'] ;
		$_SESSION['info_book']['nofpages'] = $_POST['nofpages'] ;
		$_SESSION['info_book']['year1'] = $_POST['year1'] ;
		$_SESSION['info_book']['year2'] = $_POST['year2'] ;
		$_SESSION['info_book']['keywords'] = $_POST['keywords'] ;
		$_SESSION['info_book']['abstract'] = $_POST['abstract'] ;
		$_SESSION['info_book']['author'] = $_POST['author'] ;
		$_SESSION['info_book']['category'] = $_POST['category'] ;
		$_SESSION['info_book']['price'] = $_POST['price'] ;
		$_SESSION['info_book']['image'] = $_POST['image'];
	}
	

	if(isset($_POST['sssxxx'])){
		
		for($i=1 ;$i<=3*($_SESSION['info_book']['author']); $i+=3){
				$title_author = "author".$i ;
				$i1 = $i+1 ;
				$i2 = $i+2 ;
				
				$num_author = (int)(($i/3)+1) ;
				
				$firstname_author = "author".$i1;
				$lastname_author = "author".$i2;
				
				$title = $_POST[$title_author];
				$first = $_POST[$firstname_author];
				$last = $_POST[$lastname_author];
				
				if(!preg_match("#^[a-zA-Z]+$#",$title))
					die("invalid title for author ".$num_author."<br/><a href=home.php>home</a>");
				
				if(!preg_match("#^[a-zA-Z]+$#",$first))
					die("invalid firstname for author ".$num_author."<br/><a href=home.php>home</a>");
				
				if(!preg_match("#^[a-zA-Z]+$#",$last))
					die("invalid lastname for author ".$num_author."<br/><a href=home.php>home</a>");
		}
		
		$id_book = add_a_book( $conn, $_SESSION['info_book']['title'] , $_SESSION['info_book']['subtitle'] , 
							   $_SESSION['info_book']['publisher'] , $_SESSION['info_book']['pulisher_link'] ,
					           $_SESSION['info_book']['id_language'] , $_SESSION['info_book']['id_olanguage'], 
							   $_SESSION['info_book']['isbn'] , $_SESSION['info_book']['yofp'] , 
							   $_SESSION['info_book']['nofpages'] ,
							   $_SESSION['info_book']['year1'] . "-" . $_SESSION['info_book']['year2'] , 
							   $_SESSION['info_book']['keywords'] ,  
							   $_SESSION['info_book']['price'], $_SESSION['info_book']['abstract'], 
							   $_SESSION['info_book']['category'], $_SESSION['info_book']['image'],
							   $_SESSION['login']['id_user']) ;

		
		if(!is_bool($id_book)){
			for($i=1 ;$i<=3*($_SESSION['info_book']['author']); $i+=3){
				$title_author = "author".$i ;
				$i1 = $i+1 ;
				$i2 = $i+2 ;
				
				$num_author = (int)(($i/3)+1) ;
				
				$firstname_author = "author".$i1;
				$lastname_author = "author".$i2;
				
				$title = $_POST[$title_author];
				$first = $_POST[$firstname_author];
				$last = $_POST[$lastname_author];
				
				
				$id_author = add_author( $conn , $title , $first , $last , $id_book ) ;
				//assign_book_author($conn,$id_book,$id_author);
			}
		}
		
		$path_1_1 = "./images/".$_SESSION['info_book']['title'] ;
		if( mkdir($path_1_1) ){
			for($i=1 ;$i<=$_SESSION['info_book']['image']; $i++){
				$file_x = "file".$i ;
				if (is_uploaded_file($_FILES[$file_x]['tmp_name']) && image_type($_FILES[$file_x]['type']) == true ){
						$type = explode('/',$_FILES[$file_x]['type']) ;					
						$path_2_2 = $_SESSION['info_book']['title']. "_" . $i . "." .$type[1] ;
						if (!move_uploaded_file($_FILES[$file_x]['tmp_name'], $path_1_1."/".$path_2_2))
						{
							delete_a_book_with_comments_authors_ratings($id_book,$conn);
							die("invalid file(s) !") ;
						}	
				}
				else{
					delete_a_book_with_comments_authors_ratings($id_book,$conn);
					die("invalid file(s) !") ;
				}
			}
		}
			
		header("Location: home.php");
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
	
	<a href="home.php" class="w3-bar-item w3-button w3-theme-l1" >Home</a>
	
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
	
	<form method=post action="<?php echo $_SERVER['PHP_SELF'];?>">
	username:
	<input type=text name=username />
	password:
	<input type=password name=password /> 
	<input type=submit name=login value=login /> 
	</form>
	
	<?php
		}
		
		else {
			for($i=1;$i<=23;$i++)
				echo '<a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">       </a>';
	?>
	
	<form method=post action="<?php echo $_SERVER['PHP_SELF'];?>">
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


<!-- pattern="^[a-zA-Z]+$" -->
	
<center>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<br/><br/><br/>
	<?php
		for($i=1 ;$i<=3*($_SESSION['info_book']['author']); $i+=3){
	?>
			title: <input type=text name="<?php echo "author".$i; ?>" required  /><br/><br/>
			<?php $i1 = $i+1 ; $i2 = $i+2 ; ?>
			firstname: <input type=text name="<?php echo "author".$i1; ?>" required pattern="^[a-zA-Z]+$" /><br/><br/>
			lastname: <input type=text name="<?php echo "author".$i2; ?>" required  pattern="^[a-zA-Z]+$" /><br/><br/><br/>
	<?php
		}
	?>
	<br/><br/><br/>
	<?php
		for($i=1 ;$i<=$_SESSION['info_book']['image']; $i++){
	?>
			<input type="file" name="<?php echo "file".$i ; ?>" required /><br/>
			<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
	<?php
		}
	?>
	<br/><br/> 
	<input type=submit name=sssxxx value=Add />
	</form>
	</center>
<?php
	

//-----------------------------------------------------------------print_all_users_not_confirmed--------------------------------------------------------
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
}
?>



