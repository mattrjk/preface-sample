<!-- this script is used to check and see if the username for a new admin account already exists when registering a new admin user-->

<?php
  //open connection to database
	require("includes/common.php");
	
	$email_hash = sha1($_REQUEST['username']); //hash it to compare to database

	if (!empty($_REQUEST['username'])) {
	 	$username_check_query = "SELECT COUNT(email_hash) FROM admin_users WHERE email_hash = '$email_hash'";
	 	
	 	try {
		 	$ucheck_stmt = $db->query($username_check_query);    
		 	$ucheck_hint = $ucheck_stmt->fetchColumn(); //query database and try to get first column of matching admin user
	 	}
	 	
	 	catch(PDOException $ex) {
		 	die("Failed to run admin user ID check query: " . $ex->getMessage());
	 	}
	 	
	 	if($ucheck_hint == 0) { // if no column was returned above, then the user doesn't exist and returns true for JQuery validate plugin
		 	echo "true";
	 	}
	 	
	 	else {
		 	echo "false";
	 	}
 	}