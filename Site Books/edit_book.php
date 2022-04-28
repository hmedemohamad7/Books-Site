
<?php 
	session_start();
	require("fonction.php") ;
	
	$conn = connect_to_database("localhost", "root", "", "PROJET_PHP_V3_try") ;

if(isset($_SESSION['book'])){
if(isset($_SESSION['login'])){
		if(($_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false) && his_book($conn,$_SESSION['book']['id_book'],$_SESSION['login']['id_user'])){
	
	

if(isset($_POST['sss'])){
	
	if(!preg_match("#^[a-zA-Z ]+$#",$_POST['subtitle']))
			die("<h1>invalid subtitle</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[a-zA-Z]+$#",$_POST['publisher']))
			die("<h1>invalid publisher</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^www[.][a-z0-9][a-z0-9-._]*[a-z0-9]{1,}[.][a-z]{2,}$#",$_POST['publisher_link']))
			die("<h1>invalid publisher link</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[0-9]+([0-9]*(-)?)+[0-9]+$$#",$_POST['isbn']))
			die("<h1>invalid eBook isbn</h1><a href=home.php>home</a>") ;
		
		if(!preg_match("#^[a-zA-Z0-9_.]+$#",$_POST['keywords']))
			die("<h1>invalid keywords</h1><a href=home.php>home</a>") ;	
	
	
	update_the_book($conn,$_SESSION['book']['id_book'],$_SESSION['book']['title'],$_POST['subtitle'],$_POST['isbn'],$_POST['yofp'],
					$_POST['year1'],$_POST['year2'],$_POST['keywords'],$_POST['price'],$_POST['language'],$_POST['olanguage'],
					$_POST['publisher'],$_POST['publisher_link'],$_POST['category'],$_POST['abstract']);
					
	header("Location: book_details.php?v=".$_SESSION['book']['id_book']);
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
	<form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method="post" >
	<br/><br/><br/>
	
	subtitle: <input type=text name=subtitle value=<?php echo $_SESSION['book']['subtitle'] ;?> required pattern="^[a-zA-Z ]+$" /> <br/><br/>
	publisher: <input type=text name=publisher value=<?php echo $_SESSION['book']['publisher'] ;?> required pattern="^[a-zA-Z]+$" /> <br/><br/>
	publisher link: <input type=text name=publisher_link value=<?php echo $_SESSION['book']['publisherlink'] ;?> required pattern="^www[.][a-z0-9][a-z0-9-._]*[a-z0-9]{1,}[.][a-z]{2,}$" /><br/><br/>
	
	<?php
		$conn = connect_to_database("localhost", "root", "", "PROJET_PHP") ;	
		$stmt = $conn->query("select * from languages");
	?>
	
	language: <select name=language>
	          <?php
				for($i=1;$i<=$stmt->num_rows;$i++){
					$row = $stmt->fetch_assoc();
					if($row['id_language'] == $_SESSION['book']['id_language']){
						echo "<option value = " .$row['id_language']. " selected >" . $row['name_language'] . "</option>";
					}
					else{
						echo "<option value = " .$row['id_language']. ">" . $row['name_language'] . "</option>";
					}
				}
			  ?>
			  </select><br/><br/>
			  
    Origin language: <select name=olanguage>
	          <?php
				
				$stmt = $conn->query("select * from languages");
				for($i=1;$i<=$stmt->num_rows;$i++){
					$row = $stmt->fetch_assoc();
					if($row['id_language'] == $_SESSION['book']['id_originlanguage']){
						echo "<option value = " .$row['id_language']. " selected >" . $row['name_language'] . "</option>";
					}
					else{
						echo "<option value = " .$row['id_language']. ">" . $row['name_language'] . "</option>";
					}
				}
			  ?>
			  </select><br/><br/>
	
	ebook ISBN: <input type=text name=isbn value=<?php echo $_SESSION['book']['ebookISBN'] ;?> required pattern="^[0-9]+([0-9]*(-)?)+[0-9]+$" /> <br/><br/>
	year of publication: <input type=number name=yofp value=<?php echo $_SESSION['book']['year_of_pub'] ; ?> required /> <br/><br/>
	Number of pages: <input type=number min=5 name=nofpages value=<?php echo $_SESSION['book']['num_of_pages'] ;?> required /> <br/><br/>
	<?php $tab = explode("-",$_SESSION['book']['age_range']) ; ?>
	Age range: <input type=number width=4 min=0 name=year1 value=<?php echo $tab[0] ;?> required /> to <input type=number value=<?php echo $tab[0] ;?> width=4 min=0 name=year2 /> <br/><br/>
	
	keywords: <input type=text name=keywords value=<?php echo $_SESSION['book']['keywords'] ; ?> required pattern="^[a-zA-Z0-9_.]+$" /> <br/><br/>
	price: <input type=number min=0 name=price value=<?php echo $_SESSION['book']['price'] ; ?> required /> <br/><br/>
	
	
	<br/><br/><br/>
	abstract:<br/> <textarea name=abstract height=40 width=20 required ><?php echo $_SESSION['book']['abstract'] ; ?></textarea><br/><br/>
	
	
	<!--<br/>Number of Authors: <input type=number min=1 value=1 name=author required /> <br/>
	<br/>Number of Images: <input type=number min=1 max=5 value=1 name=image required /> <br/><br/>-->
	
	category: 
		  <select name=category>
		  <?php
		  $stmt2 = $conn->query("select * from categories where EEE = 1");
		  for($i=1;$i<=$stmt2->num_rows;$i++){
					$row2 = $stmt2->fetch_assoc();
					if($row2['id_category'] == $_SESSION['book']['id_category']){
						echo "<option value=".$row2['id_category']." selected >";
						echo $row2['name_category'];
						echo "</option>" ;
					}
					else{
						echo "<option value=".$row2['id_category'].">";
						echo $row2['name_category'];
						echo "</option>" ;
					}
		  }
		  ?>
		  </select>
	<br/><br/> 
	<input type=submit name=sss value=Edit />
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



