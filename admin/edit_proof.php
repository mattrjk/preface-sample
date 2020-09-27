<!-- page that provides all the detailed information about the order, including revisions sent and changes provided by client. Also includes form to submit revisions for client review. -->

<?php

//open connection to database and check for logged-in user
	require("includes/common.php");

	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php"); 
	} 

  //query to get all details about order
	$order_id = $_REQUEST['order_id'];
	
	$order_query = "SELECT * FROM proofs WHERE (order_id = '$order_id') ORDER BY revision DESC";
	
	try { 
    	$order_stmt = $db->query($order_query); 
	} 

	catch(PDOException $ex) { 
	    die("Failed to run order query: " . $ex->getMessage()); 
	} 
         
 	while ($u = $order_stmt->fetch(PDO::FETCH_ASSOC)) {
    	$order_array[] = $u;
  	}
  
	$order_info = $order_array[0];
	
	$revision = $order_info['revision'];
	$revision += 1; //increment revision count for hidden variable on submit revision form
  
  // query to get all client details for submitting a revision since it was viewed as a new line in the database
  // TODO: come up with a more elegant way of doing revisions. At the very least, try to get rid of that mess of hidden input fields below
	$client_query_params = array(
		':client_name' => $order_info['client_name']
	);

	$client_query = "SELECT * FROM client_list WHERE (client_name = ':client_name')";
	
	try {
		$client_stmt = $db->query($client_query);
	}
		
	catch(PDOException $ex) {
		die("Failed to run client query: " . $ex->getMessage());
	}
	
	while ($u = $client_stmt->fetch(PDO::FETCH_ASSOC)) {
		$client_array[] = $u;
	}
	
	$client_info = $client_array[0];
  
  //get all copies sent to third parties by client for table
	$copy_query = "SELECT * FROM copies WHERE (order_id = '$order_id') ORDER BY created ASC";
	
	try {
		$copy_stmt = $db->query($copy_query);
	}
	
	catch(PDOException $ex) {
		die("Failed to run copy query " . $ex->getMessage());
	}
	
	while ($u = $copy_stmt->fetch(PDO::FETCH_ASSOC)) {
		$copy_array[] = $u;
	}

  // get count of billable revisions for ease of use when creating final invoice for customer order
	$billable_revision_stmt = "SELECT sum(billable_revision) AS total FROM proofs WHERE (order_id = '$order_id')";

	try {
		$billable_revision_query = $db->query($billable_revision_stmt);
		$billable_revision_total = $billable_revision_query->fetchColumn();
	}

	catch(PDOException $ex) {
		die("Failed to run billable revision query " . $ex->getMessage());
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Edit Order - Preface</title>
		<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
		<link rel="stylesheet" href="assets/css/layout.css">
		<link rel="stylesheet" href="assets/css/style.css">		
		<link href="assets/css/jquery-ui.theme.css" rel="stylesheet">
		<link href="assets/css/jquery-ui.structure.css" rel="stylesheet">
	</head>
	<body>
		<div id="submit-revision-confirm-dialog" title="Ready to Send Revision?">
			<p>You're about to send this revision to the client. Are you sure you want to continue?</p>
		</div>

		<?php include 'includes/header.php'; ?>
		<?php include 'includes/sidebar.php'; ?>
		<div class="content">
			<div class="section group">
				<div class="col span_2_of_3"> <!-- Main client info area -->
					<h1>Order ID: <?php echo $order_info['order_id']; ?></h1>
					<h3>Customer ID: <?php echo $order_info['customer_id']; ?></h3>
					<h3>Customer Name: <?php echo $order_info['client_name']; ?></h3>
					<h3>Order Description: <?php echo $order_info['order_description']; ?></h3>
					<h3>Total Billable Revisions: <?php echo $billable_revision_total; ?></h3>
					<h3>Latest Proof Submitted: <?php echo $order_info['created']; ?></h3>
					<h3>Latest Approval Submitted: <?php echo $order_info['approved_date']; ?></h3>
					<h3>Customer Access URL: <a href="https://proofs.domain.com/o/<?php echo $order_info['customer_id']; ?>/<?php echo $order_info['order_id']; ?>" target="_blank">https://proofs.domain.com/o/<?php echo $order_info['customer_id']; ?>/<?php echo $order_info['order_id']; ?></a></h3>
					<h3>Emailed to: <?php echo $order_info['sent_to']; ?></h3>
					<?php if(isset($order_info['cc'])) { echo '<h3>CC\'ed to: ' . $order_info['cc'] . '</h3>'; } ?>
					<h3>Uploaded by: <?php echo $order_info['sent_by']; ?></h3>
				</div>
				<div class="col span_1_of_3"> <!-- Revision form -->
					<h2>Submit Revision</h2>
					<h4>This will automatically be sent to the same people all previous revisions were sent to.</h4>
					<form action="actions/submit_revision.php" method="post" class="default_form" id="submit-revision" enctype="multipart/form-data">
						<ul class="default_form">
					    	<li><label for="revision_description">Revision Description:</label></li>
							<li><input type="name" name="revision_description" id="revision_description" placeholder="Replaced awardee names"><br /><label for="revision_description" class="error"></label></li>
							<li><input type="checkbox" id="billable_revision" name="billable_revision" value="1" style="width:auto;"><label for="billable_revision">Billable Revision</label></li>
					    	<li><label for="revision_file">New Proof File:</label></li>
							<li><input type="file" id="revision_file" name="revision_file"><br /><label for="revision_file" class="error"></label></li>							
							<li>
								<input type="hidden" id="new_revision" name="new_revision" value="<?php echo $revision; ?>">
								<input type="hidden" id="salutation" name="salutation" value="<?php echo $client_info['default_name']; ?>">
								<input type="hidden" id="order_id" name="order_id" value="<?php echo $order_info['order_id']; ?>">
								<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $order_info['customer_id']; ?>">
								<input type="hidden" id="client_name" name="client_name" value="<?php echo $order_info['client_name']; ?>">
								<input type="hidden" id="sent_to" name="sent_to" value="<?php echo $order_info['sent_to']; ?>">
								<input type="hidden" id="salutation" name="salutation" value="<?php echo $order_info['fullname']; ?>">
								<input type="hidden" id="first_name" name="first_name" value="<?php echo $order_info['first_name']; ?>">
								<input type="hidden" id="cc" name="cc" value="<?php echo $order_info['cc']; ?>">
								<input type="hidden" id="order_description" name="order_description" value="<?php echo $order_info['order_description']; ?>">
							</li>
					    	<li class="button"><button type="submit" class="submit">Submit Revision</button></li>
						</ul>
					</form>
				</div>
			</div>	
			
			<hr>
			
			<div class="section group">				
				
				<div class="col span_3_of_3"> <!-- table of all revisions sent by us to client -->
					<h2>Proof History:</h2>
					<table class="striped rounded" style="max-width:1400px;">
						<thead>
              <tr>
								<th>Proof Version</th>
								<th>Date Sent</th>
								<th>Revision Description</th>
								<th>Billable Revision</th>
								<th>Changes Requested</th>
								<th>Proof Status</th>
								<th>Date Approved</th>
                <th>Edit Status</th>
              </tr>
						</thead>
						<tbody>
							<?php foreach ($order_array as $proof): ?>
								<tr>
									<td><a href="https://prooffiles.domain.com/<?php echo $proof['proof_file']; ?>" target="_blank"><?php echo $proof['revision']; ?></a></td>
									<td><?php echo $proof['created']; ?></td>
									<td><?php echo $proof['revision_description']; ?></td>
									<td><?php if($proof['billable_revision'] == 1) {
											echo 'Yes';
										}
										else {
											echo 'No';
										}
									?>
									<td><?php echo $proof['changes']; ?></td>
									<td>
										<?php
										
											if($proof['approval_status'] == 0) {
												echo '<span class="proof_pending">Ready for approval</span>';
											}
										
											if($proof['approval_status'] == 1) {
												echo '<span class="proof_approved">Approved</span>';
											}
											
											if($proof['approval_status'] == 2) {
												echo '<span class="proof_approved">Approved w/changes</span>';
											}
					
											if($proof['approval_status'] == 3) {
												echo '<span class="proof_not_approved">Not approved</span>';
											}
											
										?> <!-- TODO: seems sloppy with ints? -->
									</td>
									<td><?php echo $proof['approved_date']; ?></td>
									<td><a href="edit_status_pop.php?order_id=<?php echo $proof['order_id']; ?>&revision=<?php echo $proof['revision']; ?>" onclick="NewWindow(this.href,'mywin','660','660','yes','center');return false" onfocus="this.blur()">Click here</a></td> <!-- popup window to manually change approval status in case of customer mistake -->
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="section group <?php if(isset($copy_array)) {echo 'copy-exist';} else {echo 'hide-copy';} ?> "> <!-- table to show all non-actionable copies sent by customer to third-party. Hide whole thing if none sent -->
				<div class="col span_1_of_3">
					<h2>Copy History:</h2>
					<table class="striped rounded">
						<thead>
							<tr>
								<th>Date</th>
								<th>Sent By</th>
								<th>Sent To</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($copy_array as $copy): ?>
								<tr>
									<td><?php echo $copy['created']; ?></td>
									<td><a href="mailto:<?php echo $copy['originator_email']; ?>"><?php echo $copy['originator_name']; ?></a></td>
									<td><a href="mailto:<?php echo $copy['copy_email']; ?>"><?php echo $copy['copy_name']; ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>								
			</div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script><script src="../assets/js/jquery-ui.min.js"><\/script>')</script>
		<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
		<script type="text/javascript" src="assets/js/additional-methods.js"></script>
		<script src="assets/js/edit-proof-ui.js"></script>
		<script src="assets/js/edit-proof-validate.js"></script>
		<script src="assets/js/popup.js"></script>
		<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
    <script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
    <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	</body>
</html>