<!-- Despite the name this file is used for form validation on the main client index.php page. It checks to see if either the provided Customer ID or Order ID are valid. This query is trigged by JQuery form validation when the customer submits the input field. Doing this for better UX on form...they can see which ID number they got wrong right away without redirect-->

<?php

	require("includes/common.php");
	
	$customer_id = $_REQUEST['customer_id'];
	$order_id = $_REQUEST['order_id'];

	if (!empty($_REQUEST['customer_id'])) {
	 	$customer_check_query = "SELECT COUNT(customer_id) FROM proofs WHERE ($customer_id = customer_id)";
	 	
	 	try {
		 	$ccheck_stmt = $db->query($customer_check_query);    
		 	$ccheck_hint = $ccheck_stmt->fetchColumn();
	 	}
	 	
	 	catch(PDOException $ex) {
		 	die("Failed to run client ID check query: " . $ex->getMessage());
	 	}
	 	
	 	if($ccheck_hint == 0) {
		 	echo "false";
	 	}
	 	
	 	else {
		 	echo "true";
	 	}
 	}
 	
    if (!empty($_REQUEST['order_id'])) {
	 	$order_check_query = "SELECT COUNT(order_id) FROM proofs WHERE ($order_id = order_id)";
	 	
	 	try {
		 	$ocheck_stmt = $db->query($order_check_query);    
		 	$ocheck_hint = $ocheck_stmt->fetchColumn();
	 	}
	 	
	 	catch(PDOException $ex) {
		 	die("Failed to run order ID check query: " . $ex->getMessage());
	 	}
	 	
	 	if($ocheck_hint == 0) {
		 	echo "false";
	 	}
	 	
	 	else {
		 	echo "true";
	 	}
 	}