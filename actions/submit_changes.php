<!-- Despite its name, this query was used to process both approval and changes -->

<?php

	require("includes/common.php");
	require_once '../assets/lib/signature-to-image.php';
  require("../vendor/autoload.php");
  
  // Azure blob storage used for signature file storage
	use MicrosoftAzure\Storage\Blob\BlobRestProxy;
	use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
	use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
  use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
  
  // MailChimp used to add customers to newsletter list
	use \DrewM\MailChimp\MailChimp;
	$MailChimp = new MailChimp(getenv("MAILCHIMP_API_KEY"));

	$order_id = $_POST['order_id'];
	$client_id = $_POST['customer_id'];
	$client_name = $_POST['client_name'];
	$order_description = $_POST['order_description'];
	$changes = $_POST['changes'];
	$approval = $_POST['approval'];
	if(isset($_POST['join_mailer'])) { $want_mailer = $_POST['join_mailer']; } //only set if the customer checked the box to be added to the newsletter
	$approved_date = date('D, M j, Y, g:ia');
	$revision = $_POST['revision'];
	
	if($approval == "not_approved") {
		$finalized = 0;
		$approval_status = 3;
	}
	
	if($approval == "approved_as_is") {
		$finalized = 1;
		$approval_status = 1;
	}
	
	if($approval == "approved_pending") {
		$finalized = 1;
		$approval_status = 2;
  }
  
  // TODO: something better...I can never remember which int is which approval status

  // Signature pad adapted from: https://github.com/thread-pond/signature-pad
	$sig = $_POST['output'];
	$name = $_POST['name'];
	$signer_email = $_POST['client_email'];
	$sig_hash = sha1($sig);

	$imgjson = $_POST['output'];
	$img = sigJsonToImage($imgjson, array('imageSize'=>array(450, 125)));

	ob_start();
	$isimg = imagepng($img);
	$sigimage = ob_get_contents();
	ob_end_clean();
	$dest = "signatures/" . $signer_email . "-" . date('D-M-j-Y-g-ia') . ".png";

	$connectionString = getenv('AZURE_BLOB_CONNECTION_STRING');
	$blobClient = BlobRestProxy::createBlobService($connectionString);
  
  // Signature pad outputs data as JSON vector data, converted above to PNG, then uploaded to Azure for use in transaction alert email
	if($isimg == 'true') {
		try {
			$blobClient->createBlockBlob("prooffiles", $dest, $sigimage);
		}

		catch(ServiceException $e){
			$code = $e->getCode();
			$error_message = $e->getMessage();
			echo $code.": ".$error_message."<br />";
		}

		imagedestroy($img);
		
		include '../includes/submit_changes_header.php';

		try {
			$isExist = $blobClient->getBlob("prooffiles", $dest);
		}

		catch(ServiceException $e){
			echo $e;
    }
    
    //above: check to make sure signature was uploaded successfully, if yes, continue to below. Below: all comments apply to each block. See comments at end.

		if ($isExist) { // check to make sure signature was uploaded successfully to Azure
			if(isset($approval)) { // check to make sure a radio button was checked on the form
				if($approval == "approved_as_is") { // client checked approved as is on the form
					if($changes == null) {
						$changes = "None"; //sometimes clients like to include notes in the changes box that are not necessarily changes. For clarity sake, set to None string rather than null entry if they left the box blank
					} 
					
					try {
            // update values for specific revision in proofs table
						$approve_query = "UPDATE proofs SET approval_status = :approval_status, approved_date = :approved_date, approver = :approver, approver_email = :approver_email, changes = :changes, finalized = :finalized WHERE ('$order_id' = order_id AND '$revision' = revision)";
						
						$approve_query_params = array(
							':approval_status' => $approval_status,
							':approved_date' => $approved_date,
							':approver' => $name,
							':approver_email' => $signer_email,
							':changes' => $changes,
							':finalized' => $finalized
						);
						$approve_stmt = $db->prepare($approve_query);
						$approve_result = $approve_stmt->execute($approve_query_params);

            // add new signature JSON
            //TODO: Do we need this? It's huge
						$sig_sql = "INSERT INTO signatures (signator, signature, sig_hash, sig_file) VALUES (:signator, :signature, :sig_hash, :dest)";
						$signature_stmt = $db->prepare($sig_sql);
						$signature_stmt->bindValue(':signator', $name, PDO::PARAM_STR);
						$signature_stmt->bindValue(':signature', $sig, PDO::PARAM_STR);
						$signature_stmt->bindValue(':sig_hash', $sig_hash, PDO::PARAM_STR);
						$signature_stmt->bindValue(':dest', $dest, PDO::PARAM_STR);
            $signature_stmt->execute();
            
            //TODO: Pick a lane! Both bindValue and passing the array accomplish the same thing. Research which is better? Likely the array for reasons in comments at end

            // use Postmark to send transaction alert receipt to client
						$isSent = postmarkSendApproval($signer_email, $name, $client_name, $order_description, "Approved", $changes, $dest, XXX, $_POST['cc']);

						if ($isSent <> 0) { // Check for successful Postmark send and include blurb to client on next page
							echo $isSent;
							include '../includes/submit_changes_unsuccessful.php';			    	
						}
				
						else {
							include '../includes/approved_successful.php';
						}
					}

				catch(PDOException $ex) { 
						die("Failed to run approval query: " . $ex->getMessage()); 
					} 
				}

				if($approval == "approved_pending") { //client checked approved with changes, comments above apply here too
					try {
						$approve_query = "UPDATE proofs SET approval_status = :approval_status, approved_date = :approved_date, approver = :approver, approver_email = :approver_email, changes = :changes, finalized = :finalized WHERE ('$order_id' = order_id AND '$revision' = revision)";
						
						$approve_query_params = array(
							':approval_status' => $approval_status,
							':approved_date' => $approved_date,
							':approver' => $name,
							':approver_email' => $signer_email,
							':changes' => $changes,
							':finalized' => $finalized
						);
						$approve_stmt = $db->prepare($approve_query);
						$approve_result = $approve_stmt->execute($approve_query_params);

						$sig_sql = "INSERT INTO signatures (signator, signature, sig_hash, sig_file) VALUES (:signator, :signature, :sig_hash, :dest)";
						$signature_stmt = $db->prepare($sig_sql);
						$signature_stmt->bindValue(':signator', $name, PDO::PARAM_STR);
						$signature_stmt->bindValue(':signature', $sig, PDO::PARAM_STR);
						$signature_stmt->bindValue(':sig_hash', $sig_hash, PDO::PARAM_STR);
						$signature_stmt->bindValue(':dest', $dest, PDO::PARAM_STR);
						$signature_stmt->execute();

						$isSent = postmarkSendApproval($signer_email, $name, $client_name, $order_description, "Approved Pending Changes", $changes, $dest, XXX, $_POST['cc']);

						if ($isSent <> 0) {
							echo $isSent;
							include '../includes/submit_changes_unsuccessful.php';			    	
						}
				
						else {
							include '../includes/pending_changes_successful.php';
						}
					}

					catch(PDOException $ex) { 
						die("Failed to run approval query: " . $ex->getMessage()); 
					}
				}

				if($approval == "not_approved") { // client selected not approved
					try {
						$approve_query = "UPDATE proofs SET approval_status = :approval_status, approved_date = :approved_date, approver = :approver, approver_email = :approver_email, changes = :changes, finalized = :finalized WHERE (order_id = '$order_id' AND revision = '$revision')";
						
						$approve_query_params = array(
							':approval_status' => $approval_status,
							':approved_date' => $approved_date,
							':approver' => $name,
							':approver_email' => $signer_email,
							':changes' => $changes,
							':finalized' => $finalized
						);
						$approve_stmt = $db->prepare($approve_query);
						$approve_result = $approve_stmt->execute($approve_query_params);

						$sig_sql = "INSERT INTO signatures (signator, signature, sig_hash, sig_file) VALUES (:signator, :signature, :sig_hash, :dest)";
						$signature_stmt = $db->prepare($sig_sql);
						$signature_stmt->bindValue(':signator', $name, PDO::PARAM_STR);
						$signature_stmt->bindValue(':signature', $sig, PDO::PARAM_STR);
						$signature_stmt->bindValue(':sig_hash', $sig_hash, PDO::PARAM_STR);
						$signature_stmt->bindValue(':dest', $dest, PDO::PARAM_STR);
						$signature_stmt->execute();

						$isSent = postmarkSendApproval($signer_email, $name, $client_name, $order_description, "Not Approved", $changes, $dest, XXX, $_POST['cc']);

						if ($isSent <> 0) {
							echo $isSent;
							include '../includes/submit_changes_unsuccesful.php';			    	
						}
				
						else {
							include '../includes/changes_requested_successful.php';
						}
					}

					catch(PDOException $ex) { 
						die("Failed to run approval query: " . $ex->getMessage()); 
					}
						
        }
        
        //TODO: in addition to determining bindValue vs array, these if blocks need to be combined somehow. The only difference is the $status var being passed with postmarkSendApproval...which is not being used in the actual template? Works, but check later and make this look nicer
				
				if($want_mailer == true) { //if customer wants to be added to newsletter, send to MailChimp and add the thank you note on the next page
					$list_id = 'XXX';

					$mailer_result = $MailChimp->post("lists/$list_id/members", [
						'email_address' => $signer_email,
						'status'        => 'subscribed',
						'double_optin'	=> false
					]);
					
					include '../includes/subscribe_successful.php';			
				}
				
				include '../includes/submit_changes_footer.php';
			}
		}

	else {
    include '../includes/submit_changes_unsuccessful.php';
    //TODO: something more elegant. Custom error codes?
	}
}