<!-- query and script for uploading proof and sending transaction alert email to client -->
<!-- TODO: combine this and add_proof.php for code reuse -->

<?php
  //open connection to database
	require("includes/common.php");
	require("../../vendor/autoload.php");
	use MicrosoftAzure\Storage\Blob\BlobRestProxy;
	use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
	use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
	use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

  // create a random number for file name. We're doing random as a temporary stop gap because the proof files come out of engraving software with spaces
  // TODO: make this make sense and use pertinent information, like a combination of order ID and revision number
	$ext = "pdf"; // TODO: adjust for when we support other file formats
	$ran = rand();
	$ran2 = $ran.".";

	$blobTarget = "proof_files/";
	$blobTarget = $blobTarget.$ran2.$ext;

  // connect to Azure blob storage
	$connectionString = getenv('AZURE_BLOB_CONNECTION_STRING');
	$blobClient = BlobRestProxy::createBlobService($connectionString);
	$blobContent = fopen($_FILES['revision_file']['tmp_name'], 'rb');
	$blobOptions = new CreateBlockBlobOptions();
	$blobOptions->setContentType("application/pdf");

	$uploaded_proof = $blobTarget; // used on the client side for URL of embedded inline PDF proof
	$approved = 0; // because this is the int associated with ready for approval
	
	$proof_submit_sql = "INSERT INTO proofs (order_id, customer_id, client_name, order_description, revision_description, proof_file, revision, sent_to, fullname, first_name, cc, created, sent_by, approval_status, billable_revision) VALUES (:order_id, :customer_id, :client_name, :order_description, :revision_description, :proof_file, :revision, :sent_to, :fullname, :first_name, :cc, :created, :sent_by, :approval_status, :billable_revision)";
	
	$proof_submit_params = array(
		':order_id' => $_POST['order_id'],
		':customer_id' => $_POST['customer_id'],
		':client_name' => $_POST['client_name'],
		':order_description' => $_POST['order_description'],
		':revision_description' => $_POST['revision_description'],
		':proof_file' => $uploaded_proof,
		':revision' => $_POST['new_revision'], // TODO: this will likely be the part that needs to be updated to combine with add_proof.php
		':sent_to' => $_POST['sent_to'],
		':fullname' => $_POST['salutation'],
		':first_name' => $_POST['first_name'],
		':cc' => $_POST['cc'],
		':created' => date('D, M j, Y, g:ia'),
		':sent_by' => $_SESSION['user']['full_name'],
		':approval_status' => $approved,
		':billable_revision' => $_POST['billable_revision']
	);
	
	$finalized = 1; //since the old proof was approved or had changes submitted, we are done with it
	$order_id = $_POST['order_id'];
	$old_revision = $_POST['new_revision'];
	$old_revision -= 1; //get the previous revision by decrementing the new revision by one
  
  //update the previous revision's finalized status
  // TODO: another part that will likely need to be addressed for combination with add_proof.php
	$update_old_proof_sql = "UPDATE proofs SET finalized = '$finalized' WHERE (order_id = '$order_id' AND revision = '$old_revision')";

  // making sure that we actually have a file submitted by the form before starting this process
	if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['revision_file']) && $_FILES['revision_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['revision_file']['tmp_name'])) {
  
    //upload file to Azure. Adapted from Azure docs
		try {
			$blobClient->createBlockBlob("prooffiles", $blobTarget, $blobContent, $blobOptions);
		}
		
		catch(ServiceException $e){
			$code = $e->getCode();
			$error_message = $e->getMessage();
			echo $code.": ".$error_message."<br />";
		}

	}

  // check and make sure file was uploaded and exists in Azure before continuing to update database and send email to client
	try {
		$isExist = $blobClient->getBlob("prooffiles", $blobTarget);
	}

	catch(ServiceException $e){
		echo $e;
	}

	if ($isExist) { // have confirmed proof file is uploaded

		try { // add new proof details to database
			$stmt = $db->prepare($proof_submit_sql);
			$result = $stmt->execute($proof_submit_params);
		}
	    
		catch(PDOException $ex) { 
			die("Failed to submit proof query: " . $ex->getMessage());
		}
	    
		try { // update old proof details in database
			$update_old_proof_stmt = $db->prepare($update_old_proof_sql);
			$update_old_proof_result = $update_old_proof_stmt->execute();
    }
    
    // TODO: research if these two queries can be done at the same time
			
		catch(PDOException $ex) {
			die("Failed to submit old proof update: " . $ex->getMessage());
		}
    // send transaction alert email to client that the proof is ready for them to view
		$isSent = postmarkSendReady($_POST['sent_to'], $_POST['cc'], 434702, $_POST['first_name'], $_POST['order_id'], $_POST['customer_id']);

		if ($isSent <> 0) { // TODO: better error handling beyond displaying raw return from Postmark
			echo $isSent;
		}

		else { // TODO: redirect to submit_proof_success.php or popup, rather than inelegant redirect back to home page
			header("Location: ../open_proofs.php");
			die("Redirecting to ../open_proofs.php");
		}
	}

	else {
		header("Location: ../submit_proof_fail.php"); 
    	die("Redirecting to ../submit_proof_fail.php"); 
	}