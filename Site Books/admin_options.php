<?php

	if(isset($_SESSION['login'])){
		if( isset($_SESSION['login']['is_admin'])) {
			if($_SESSION['login']['is_admin'] == true){
?>
  <a class="w3-bar-item w3-button w3-hover-black" href="add_book.php">
  Add Book +
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="confirm_users.php">
  Confirm Users
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="users_ban.php">
  Ban A User
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="users_unban.php">
  Unban A User
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="confirm_categories.php">
  Confirm categories
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="add_a_user.php">
  Add a user
  </a>
  <a class="w3-bar-item w3-button w3-hover-black" href="view_reports.php">
  View All Reports !
  </a>
<?php
			}
		}
	}
	
	if(isset($_SESSION['login'])){
		if(isset($_SESSION['login']['is_admin']) && isset($_SESSION['login']['is_activated']) && isset($_SESSION['login']['is_banned'])) {
			if($_SESSION['login']['is_admin']==false && $_SESSION['login']['is_activated']==true && $_SESSION['login']['is_banned']==false){
?>
			<a class="w3-bar-item w3-button w3-hover-black" href="add_book.php">
			Add Book +
			</a>
			<a class="w3-bar-item w3-button w3-hover-black" href="Add_category.php">
			Add a category 
			</a>
<?php
			}
		}
	}
?>