
<?php 
	session_start();
	require("fonction.php") ;

if(isset($_SESSION['login'])){
		if($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false && $_SESSION['login']['is_admin']==false){
			if(isset($_GET['r'])){
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;



if(isset($_POST['send_rep'])){
	
	$id_comment = 0 ;
	if($_GET['r'] == 'c')
		$id_comment = $_SESSION['comment']['id_comment'] ;
		
		
		$stmt = $conn->prepare("insert into reports(id_user,id_comment,date,description) values(?,?,?,?)") ;
		$id_user = $_SESSION['login']['id_user'];
		$date = date("Y-m-d h:i:s");
		$desc = $_POST['ta'] ;
		
		$stmt->bind_param("iiss",$id_user,$id_comment,$date,$desc);
		$stmt->execute();

	header("Location: book_details.php?v=".$_SESSION['book']['id_book']);
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



		
		<form action="<?php echo $_SERVER['PHP_SELF']."?r=".$_GET['r'] ; ?>" method=post>
		
		
		<div class="w3-row w3-padding-64">
		<div class="w3-twothird w3-container">
		
		
		 <p>
		<textarea name=ta></textarea>
		<input type=submit name=send_rep value="Send Report"/>
		</p>
		
		
		<div class="w3-third w3-container">
		  <!--<p class="w3-border w3-padding-large w3-padding-32 w3-center">Book's Photo1</p>-->
		  
		</div>
	  </div>
	  
	  </form>
	  
<?php
	

//-----------------------------------------------------------------print_all_users_not_confirmed--------------------------------------------------------
?>
	
	
  

   <!-- <div class="w3-container w3-theme-l1">
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
}
?>



