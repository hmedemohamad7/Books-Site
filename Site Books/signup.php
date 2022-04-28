<html>
<body background="signupwall.jpg">

<?php
session_start();

if(!isset($_SESSION['login'])){
require("fonction.php");
$tri = 0 ;
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
		$res = sign_up($conn,$_POST['first'], $_POST['last'], $_POST['user'], $_POST['pass'],
					  $_POST['email'], $_POST['country'], $_POST['year']."-".$_POST['month']."-".$_POST['day'],
					  $_POST['occ'], $_POST['hobbies']) ;
	}
}
?>

<center>
<form method=post action=<?php echo $_SERVER['PHP_SELF'] ;?>>
First name: <input type=text name=first required pattern="[a-zA-Z]+" /> <br/><br/>
Last name: <input type=text name=last required pattern="[a-zA-Z]+" /> <br/><br/>
Username: <input type=text name=user required pattern="[a-zA-Z0-9_.]{4,}" />  <?php if(isset($exist)){ if($exist==true){ echo  '<font color=red>username already exists</font>' ; unset($exist); }} ?> <br/><br/>
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

<center>
</br></br>
<h1><font color=red><a href=home.php>home</a></font></h1>
</center>
<?php
}
?>
</body>
</html>