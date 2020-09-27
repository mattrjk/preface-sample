<!-- this form is in a popup window accessible from edit_proof.php. This is to be used when a customer mistakenly selects the wrong approval option -->
<!-- TODO: add cancellation option once I figure out the int messiness on edit_proof.php -->

<?php

  //open connection to database and check for logged-in user
	require("includes/common.php");
	
	$order_id = $_REQUEST['order_id'];
	$revision = $_REQUEST['revision'];
  
  //get all information about specific revision
	$order_query = "SELECT * FROM proofs WHERE (order_id = '$order_id' AND revision = '$revision') ORDER BY revision DESC";
	
	try { 
        $order_stmt = $db->query($order_query); 
    } 

    catch(PDOException $ex) { 
        die("Failed to run client query: " . $ex->getMessage()); 
    } 
         
    while ($u = $order_stmt->fetch(PDO::FETCH_ASSOC)) {
        $order_array[] = $u;
    }
    
    $order_info = $order_array[0];
    
    // assign proof ID for hidden input field. Revision is already set from above
    $proof_id = $order_info['proof_id'];
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Proof Manually - Preface</title>
		<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="assets/css/layout.css">
		<link rel="stylesheet" href="assets/css/style.css">
		<link href="assets/css/jquery-ui.theme.css" rel="stylesheet">
		<link href="assets/css/jquery-ui.structure.css" rel="stylesheet">
	</head>
	<body>
		<div id="edit-proof-success-dialog" title="Proof Manually Updated">
			<p>You have successfully manually updated the proof status. Please close this window and refresh the previous page to see your changes.</p>
		</div>
		
		<div id="set-status-manual">
			<h2>Manually Set Approval Status:</h2>
			<h4>Current Status: 
				<?php
										
					if($order_info['approval_status'] == 0) {
						echo '<span class="proof_pending">Ready for approval</span>';
					}
				
					if($order_info['approval_status'] == 1) {
						echo '<span class="proof_approved">Approved</span>';
					}
					
					if($order_info['approval_status'] == 2) {
						echo '<span class="proof_approved">Approved w/changes</span>';
					}

					if($order_info['approval_status'] == 3) {
						echo '<span class="proof_not_approved">Not approved</span>';
					}
					
				?>
			</h4>
			<form action="actions/edit_status.php" method="post" class="default_form" id="set-status-manual-form">
				<ul class="default_form">
					<li><h4>Change status to:</h4>
						<select class="default_form" name="manual_status" id="manual_status">
							<option value="" selected>Choose a new status</option>
							<option value="0">Ready for approval</option>
							<option value="1">Approved</option>
							<option value="2">Approved w/changes</option>
							<option value="3">Not approved</option>
						</select><br><label for="manual_status" class="error"></label>
					</li>
					<li><label for="changes">Changes:</label></li>
					<li><textarea name="changes" id="changes" placeholder="It's a beaut, Clark!" rows="10" cols="60"></textarea><br /><label for="changes" class="error"></label></li>
					<li><input type="hidden" name="revision" id="revision" value="<?php echo $revision; ?>"></li>
					<li><input type="hidden" name="proof_id" id="proof_id" value="<?php echo $proof_id; ?>"></li>
					<li class="button"><button class="submit" type="submit">Edit Status</button></li>
				</ul>
			</form>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script><script src="../assets/js/jquery-ui.min.js"><\/script>')</script>
    <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
		<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
		<script type="text/javascript" src="assets/js/additional-methods.js"></script>
		<script src="assets/js/edit-proof-pop-ui.js"></script>
		<script src="assets/js/edit-proof-pop-validate.js"></script>
	</body>
</html>