<!-- this script is used to check if a client name already exists in the database before submitting the add client form -->

<?php
  //open connection to database
	require("includes/common.php");
	
	if (!empty($_POST['client_name'])) {

		$client_check_params = array(
			':client_name' => $_POST['client_name']
		);
	
	 	$client_check_query = "SELECT COUNT(client_name) FROM client_list WHERE client_name = :client_name";
	 	
	 	try {
		 	$ccheck_stmt = $db->prepare($client_check_query);    
			$ccheck_stmt->execute($client_check_params);
			$ccheck_hint = $ccheck_stmt->fetchColumn(); // get first column of matching client name if it exists
	 	}
	 	
	 	catch(PDOException $ex) {
		 	die("Failed to run client ID check query: " . $ex->getMessage());
	 	}
	 	
	 	if($ccheck_hint == 0) { //if no column was returned above, return true to JQuery validate
		 	echo "true";
	 	}
	 	
	 	else {
		 	echo "false";
	 	}
	 	
 	}