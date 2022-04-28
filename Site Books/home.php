<?php 
	session_start();
	require("fonction.php") ;
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;

	
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

$my_array = "" ;
$list_of_users = false ;
if(isset($_POST['slist'])){
	$top10 = true ;
	if($_POST['list']=="rate"){
		$my_array = create_array_books_average($conn) ;
	}
	elseif($_POST['list']=="cmt"){
		$my_array = create_array_books_comment($conn);
	}
	elseif($_POST['list']=="nb_books"){
		$my_array = create_array_users_books($conn) ;
		$list_of_users = true ;
	}
	elseif($_POST['list']=="----"){
		$index123 = 0 ;
		$top10 = false ;
		$stmt_tmp2 = $conn->prepare("select * from books where 1 = ?");
		$zyx = 1 ;
		$stmt_tmp2->bind_param("i",$zyx) ;
		$stmt_tmp2->execute();
		$result_tmp2 = $stmt_tmp2->get_result();
		while($row_tmp2 = $result_tmp2->fetch_assoc()){
			$my_array[$index123] = $row_tmp2 ;
			$my_array[$index123+1] = $row_tmp2['id_book'] ;
			$index123 += 2 ;
		}
	}
}
else{
	$index123 = 0 ;
	$top10 = false ;
	$stmt_tmp2 = $conn->prepare("select * from books where 1 = ?");
	$zyx = 1 ;
	$stmt_tmp2->bind_param("i",$zyx) ;
	$stmt_tmp2->execute();
	$result_tmp2 = $stmt_tmp2->get_result();
	while($row_tmp2 = $result_tmp2->fetch_assoc()){
		$my_array[$index123] = $row_tmp2 ;
		$my_array[$index123+1] = $row_tmp2['id_book'] ;
		$index123 += 2 ;
	}
	//$my_array = create_array_books_average($conn) ;	
}
$my_array = sort_array_by_option($my_array) ;


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
	
	<a href="#" class="w3-bar-item w3-button w3-theme-l1">Home</a>
	
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
		
		elseif(isset($_SESSION['login'])) {
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




  
<?php
	
	
	
	
	/*while($row = $stmt->fetch_assoc()){
		$my_table = $row ;
	}*/
	
	echo "<br/><br/>";
	if(isset($_SESSION['login'])){
		if(isset($_SESSION['login']['is_admin'])){
			if($_SESSION['login']['is_admin']==true){
?>
				<center>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post />
				<select name=list>
				<option>----</option>
				<option value=rate>Top 10 Books By Rating</option>
				<option value=cmt>Top 10 Books By Comment</option>
				<option value=nb_books>Top 10 Users (Nb of Books)</option>
				</select>
				<input type=submit name=slist value=GO />
				</form>
				</center>
<?php				
			}
		}
	}
	
	if(isset($_SESSION['login'])){
		if($_SESSION['login']['is_banned']==false && $_SESSION['login']['is_activated']){
			echo "<center>" ;
			echo "<form method=post action=".$_SERVER['PHP_SELF']." />" ;
			echo "<input type=text name=search />";
			echo "<input type=submit name=search_x value='search book' />" ;
			echo "</center>";
		}
	}
	
	if(isset($_POST['search_x'])){
		if(!empty($_POST['search'])){
			$search_array = "" ;
			$j=0;
			for($i=0 ; $i<sizeof($my_array) ; $i+=2){
				if(isset($my_array[$i]['title'])){
					if(!is_bool(strpos($my_array[$i]['title'],$_POST['search']))){
						$search_array[$j] = $my_array[$i] ;
						$j++;
						$search_array[$j] = "..." ;
						$j++;
					}
				}
			}
			
			$my_array = $search_array ;
		}
	}
	
	if(isset($my_array) && !empty($my_array)){
		if($top10==true){
			if(sizeof($my_array)<10)
				$xyz = sizeof($my_array) ;
			else
				$xyz = 10 ;
		}
		else
			$xyz = sizeof($my_array);
		
		if($list_of_users==true){
			print_top10_users($my_array,$xyz) ;
		}
		else{
			for($i=0; $i<$xyz; $i+=2){
				//$stmt2 = $conn->prepare(/*JOINTURE POUR OBTENIR NAME AUTHOR'S*/) ;
		?>
				<div class="w3-row w3-padding-64">
				<div class="w3-twothird w3-container">
				  <a href = "./book_details.php?v=<?php echo $my_array[$i]['id_book']; ?>">
					<h1 class="w3-text-teal"><?php echo $my_array[$i]['title']; ?></h1>
				  </a>
				  <?php
					$stmt2 = $conn->prepare("select name_language from languages where id_language = ?") ;
					$a = $my_array[$i]['id_language'] ;
					$stmt2->bind_param("i",$a);
					$stmt2->execute() ;
					$result = $stmt2->get_result() ;
					$row2 = $result->fetch_assoc();
				  ?>
				  <p>
				  <br/>
				  <?php
				  if(isset($_SESSION['login']) && $_SESSION['login']['is_admin']==true){
				  ?>
					<font color=red>id user : <?php echo $my_array[$i]['id_user'] ; ?></font><br/>
				  <?php
				  }
				  ?>
				  subtitle: <?php echo $my_array[$i]['subtitle']; ?><br/>
				  language: <?php echo $row2['name_language'] ;  ?><br/>
				  publisher: <?php echo $my_array[$i]['publisher'] ; ?> <br/>
				  </p>
				</div>
				<div class="w3-third w3-container">
				  <!--<p class="w3-border w3-padding-large w3-padding-32 w3-center">Book's Photo1</p>-->
				  <p <!--class="w3-border w3-padding-large w3-padding-32 w3-center"--><img src="./images/<?php echo $my_array[$i]['title']."/".$my_array[$i]['title']."_1"; ?>" width=200 height=200 /></p>
				</div>
			  </div>
		<?php
			}
			
		}
	}

	//-----------------------------------------------------------------print_all_books--------------------------------------------------------
		?>

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
\


<?php
	$conn->close();
?>



