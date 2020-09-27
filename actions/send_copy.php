<!-- This query used to add a log entry that a client sent a non-actionable copy to a third party. This was created solely for record-keeping purposes as both staff and the client could view who sent what and where. -->

<?php

	require("includes/common.php");
	require("../vendor/autoload.php");
	
	$originator_name = $_POST['originator_name'];
	$originator_email = $_POST['originator_email'];
	$copy_name = $_POST['copy_name'];
	$copy_email = $_POST['copy_email'];
	$order_id = $_POST['order_id'];
	$customer_id = $_POST['customer_id'];
	$revision = $_POST['revision'];
	$created = date('D, M j, Y, g:ia');
	
	$copy_sql = "INSERT INTO copies (originator_name, originator_email, copy_name, copy_email, order_id, customer_id, revision, created) VALUES (:originator_name, :originator_email, :copy_name, :copy_email, :order_id, :customer_id, :revision, :created)";
	$copy_params = array(
		':originator_name' => $originator_name,
		':originator_email' => $originator_email,
		':copy_name' => $copy_name,
		':copy_email' => $copy_email,
		':order_id' => $order_id,
		':customer_id' => $customer_id,
		':revision' => $revision,
		':created' => $created
	);
	
	$stmt = $db->prepare($copy_sql);
	$result = $stmt->execute($copy_params);

	$isSent = postmarkSendCopy($originator_name, $originator_email, $copy_name, $copy_email, XXXXXX, $order_id, $customer_id);

  if ($isSent <> 0) {
  	echo $isSent;
  	include '../includes/is_sent_unsuccessful.php';
  }

  else {
  	include '../includes/is_sent_successful.php';
  }