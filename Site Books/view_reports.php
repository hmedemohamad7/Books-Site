
<?php 
	session_start();
	require("fonction.php") ;

if(isset($_SESSION['login'])){
		if($_SESSION['login']['is_admin'] == true){
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;

	
if(isset($_POST['d'])){
	$stmt66 = $conn->prepare("delete FROM reports where id_report = ?");
	$stmt66->bind_param("i",$_POST['h']);
	$stmt66->execute() ;
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



	
  
<?php
	
	
	$stmt = $conn->query("SELECT * FROM reports");
	
	while($row = $stmt->fetch_assoc()){
		
		$stmt2 = $conn->prepare("select id_book from comments where id_comment = ?");
		$x12y = $row['id_comment'] ;
		$stmt2->bind_param("i",$x12y);
		$stmt2->execute();
		$result2 = $stmt2->get_result() ;
		$row2 = $result2->fetch_assoc() ;
		
		$stmt_t = $conn->prepare("select title from books where id_book = ?") ;
		$stmt_t->bind_param("i",$row2['id_book']) ;
		$stmt_t->execute();
		$result_t = $stmt_t->get_result();
		$row_t = $result_t->fetch_assoc();
?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method=post>
		<div class="w3-row w3-padding-64">
		<div class="w3-twothird w3-container">
		  
		  <p>
		     Reported by: <?php echo $row['id_user'];?> <br/>
			 Date: <?php echo $row['date']; ?> <br/>
		     On: <?php
					echo "comment id = ". $row['id_comment'] . " in book '".$row_t['title']."'" ;
        	     ?><br/>
			 description: <?php echo $row['description'] ; ?><br/>
	     </p>
		</div>
		<?php echo " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; " ; ?>
		<input type=submit name=d value=delete /><br/><br/>
		
		<input type=hidden name=h value="<?php echo $row['id_report'] ;?> ;" />
		<div class="w3-third w3-container">
		  <!--<p class="w3-border w3-padding-large w3-padding-32 w3-center">Book's Photo1</p>-->
		  
		</div>
	  </div>
	  </form>
	  <hr/>
<?php
	}

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
\


<?php
	$conn->close();
	
		}
}
?>



