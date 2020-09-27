<!-- this script is used to check if a username already exists when changing email address on edit admin form  -->

<?php
  //open connection to database
	require("includes/common.php");
	
	$email_hash = sha1($_REQUEST['email']); //hash the entered email to match database

	if (!empty($_REQUEST['email'])) {
	
		if($_SESSION['user']['username'] == $_REQUEST['email']) { //check if submitted email matches that of the currently logged in session--meaning that the admin user did not enter an updated email address and doesn't want to change it
			echo "true"; // tells JQuery validate that it's a valid entry
		}
		
		else { //if the email submitted is indeed different from the currently logged in user--meaning that the admin user DID enter an updated email address and wants to change it to that
		 	$username_check_query = "SELECT COUNT(email_hash) FROM admin_users WHERE email_hash = '$email_hash'";
		 	
		 	try {
			 	$ucheck_stmt = $db->query($username_check_query);    
			 	$ucheck_hint = $ucheck_stmt->fetchColumn(); //get first column of the matching user if it exists
		 	}
		 	
		 	catch(PDOException $ex) {
			 	die("Failed to run client ID check query: " . $ex->getMessage());
		 	}
		 	
		 	if($ucheck_hint == 0) {
			 	echo "true"; //if there is no column returned from above, that means the user doesn't exist already and so we provide a true to the JQuery validate plugin
		 	}
		 	
		 	else {
			 	echo "false";
		 	}
		}
 	}