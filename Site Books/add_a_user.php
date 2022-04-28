
<?php 
	session_start();
	require("fonction.php") ;

if(isset($_SESSION['login'])){
		if($_SESSION['login']['is_admin'] == true){
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;

	
if(isset($_POST['sss'])){
	if(isset($exist))
		unset($exist);
	$exist = check_if_username_exists($conn,$_POST['user']);
	
	if(!preg_match("#[a-zA-Z0-9_.]{4,}#",$_POST['user']))
		die("<h1>invalid username!!!</h1><br/><a href=home.php>home</a>");
	
	if(!preg_match("#[a-zA-Z0-9_.]{6,}#",$_POST['pass']))
		die("<h1>invalid password!!!</h1><br/><a href=home.php>home</a>");
	
	if(!preg_match("#^[a-z0-9-_.]+@[a-z0-9]{1}[a-z0-9-_.]*[a-z0-9]{1}\.[a-z]{2,4}$#",$_POST['email']))
		die("<h1>invalid email!!!</h1><br/><a href=home.php>home</a>");
	
	if(!preg_match("#^[a-zA-Z]+$#",$_POST['first']))
		die("<h1>invalid first name</h1><br/><a href=home.php>home</a>") ;
	
	if(!preg_match("#^[a-zA-Z]+$#",$_POST['last']))
		die("<h1>invalid last name</h1><br/><a href=home.php>home</a>") ;
	
	if(!preg_match("#^[a-zA-Z0-9_.]+$#",$_POST['country']))
		die("<h1>invalid country</h1><br/><a href=home.php>home</a>") ;
	
	if(!preg_match("#^[a-zA-Z]+$#",$_POST['occ']))
		die("<h1>invalid occupation</h1><br/><a href=home.php>home</a>") ;
	
	if(!preg_match("#^[a-zA-Z]+$#",$_POST['hobbies']))
		die("<h1>invalid hobbies</h1><br/><a href=home.php>home</a>") ;
		
	
	if($exist==false){
		$res =sign_up2($conn,$_POST['first'], $_POST['last'], $_POST['user'], $_POST['pass'],
			      $_POST['email'], $_POST['country'], $_POST['year']."-".$_POST['month']."-".$_POST['day'],
			      $_POST['occ'], $_POST['hobbies']) ;
	}
	
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



	
<center>
<form method=post action=<?php echo $_SERVER['PHP_SELF'] ;?>>
<br/><br/><br/><br/><br/>
First name: <input type=text name=first required pattern="[a-zA-Z]+" /> <br/><br/>
Last name: <input type=text name=last required pattern="[a-zA-Z]+" /> <br/><br/>
Username: <input type=text name=user required pattern="[a-zA-Z0-9_.]{4,}" /> <?php if(isset($exist)){ if($exist==true){ echo  '<font color=red>username already exists</font>' ; unset($exist); }} ?> <br/><br/>
Password: <input type=password name=pass required pattern="[a-zA-Z0-9_.]{6,}" /> <br/><br/>
email: <input type=text name=email required pattern="^[a-z0-9-_.]+@[a-z0-9]{1}[a-z0-9-_.]*[a-z0-9]{1}\.[a-z]{2,4}$" /> <br/><br/>
country: <input type=text name=country required pattern="[a-zA-Z0-9_.]+" /> <br/><br/>
Date of birth: <br/>
Day:   <select name=day>
       <?php
			for($day=1;$day<=31;$day++){
				echo "<option value=$day>";
				echo $day ;
				echo "</option>" ;
			}
	   ?>
       </select>
Month: <select name=month>
	   <?php
			for($month=1;$month<=12;$month++){
				echo "<option value=$month>";
				echo $month ;
				echo "</option>" ;
			}
	   ?>
       </select>
Year:  <select name=year>
       <?php
			for($year=2010;$year>=1960;$year--){
				echo "<option value=$year>";
				echo $year ;
				echo "</option>" ;
			}
	   ?>
       </select>
<br/><br/>
Occupation: <input type=text name=occ required pattern="[a-zA-z_.]+" /> <br/><br/>
Hobbies: <input type=text name=hobbies required pattern="[a-zA-z_.]+" /> <br/><br/>
<input type=submit name=sss />
</form>
</center>


<?php
if(isset($exist) && $exist==false){
	echo "<br/><br/><center>Registration OK!</center><br/>";
	unset($exist);
}
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
\


<?php
	$conn->close();
	
		}
}
?>



