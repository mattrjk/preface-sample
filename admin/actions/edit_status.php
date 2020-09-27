<!-- this script is used when editing the approval status of a specific revision manually -->

<?php
  //open connection to database
	require("includes/common.php");
	
	$manual_status = $_POST['manual_status'];
	$proof_id = $_POST['proof_id'];
	$approved_date = date('D, M j, Y, g:ia');
	$approver = $_SESSION['user']['full_name'];
	$approver_email = $_SESSION['user']['email'];
	$changes = $_POST['changes'];
	
	if($manual_status == 0) {
		$finalized = 0;
	}
	
	if($manual_status == 1) {
		$finalized = 1;
	}
	
	if($manual_status == 2) {
		$finalized = 1;
	}
	
	if($manual_status == 3) {
		$finalized = 0;
  }
  
  // TODO: come up with better system for this than ints as per previous files

  // query to update the specific revision being edited in the database
	$proof_submit_sql = "UPDATE proofs SET approval_status = :manual_status, approved_date = :approved_date, approver = :approver, approver_email = :approver_email, changes = :changes, finalized = :finalized WHERE  proof_id = :proof_id";
	
	$proof_submit_params = array(
		':manual_status' => $manual_status,
		':approved_date' => $approved_date,
		':approver' => $approver,
		':approver_email' => $approver_email,
		':changes' => $changes,
		':finalized' => $finalized,
		':proof_id' => $proof_id
	);
	
	try { 
        $stmt = $db->prepare($proof_submit_sql);
        $result = $stmt->execute($proof_submit_params);
    } 
    
    catch(PDOException $ex) { 
        die("Failed to run query: " . $ex->getMessage());
    }