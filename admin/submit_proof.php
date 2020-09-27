<!-- this form is used when submitting a new proof to a client -->

<?php
  //open connection to database and check for logged-in user
	require("includes/common.php");

	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php"); 
	} 

  // query all client names for dropdown
	$client_query = "SELECT id, client_name FROM client_list ORDER BY client_name";

	try {
			$client_stmt = $db->query($client_query);     
	} 

	catch(PDOException $ex) { 
			die("Failed to run client query: " . $ex->getMessage()); 
	} 
         
	while ($u = $client_stmt->fetch(PDO::FETCH_ASSOC)) {
		$client_array[] = $u;
	}
  
  // get last order ID and increment it by 1 for the new proof you are submitting right now
	$order_id_query = "SELECT order_id FROM proofs ORDER BY order_id DESC LIMIT 1";
	$order_id_stmt = $db->prepare($order_id_query);
	$order_id_stmt->execute();
	$new_order_id = $order_id_stmt->fetchColumn();
	$new_order_id += 1;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Submit Proof - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="assets/css/layout.css">
	<link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/tooltips.css">
  <link href="assets/css/jquery-ui.theme.css" rel="stylesheet">
	<link href="assets/css/jquery-ui.structure.css" rel="stylesheet">
</head>
<body>
  <!-- these modals only displayed when necessary -->
	<div id="add-client-mini-success-dialog" title="New Client Added">
		<p>Your new client has been added successfully. The page will now refresh and you can select the new customer from the dropdown menu.</p>
	</div>
	
	<div id="submit-proof-confirm-dialog" title="Ready to Send Proof">
		<p>You're about to send this proof to the client. Are you sure you want to continue?</p>
	</div>

	<script src="assets/js/submit-proof-body.js"></script>
	
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	
	<div id="add-client-mini"> <!-- this is only displayed when clicking on link to add new client -->
		<h1>Add a New Client</h1>
		<h4>The "Client Name" field is how we will identify the person or organization the proof is for in this system. This would typically be the name printed on the order form. This is solely for internal use.</h4>
		
		<h4>The "Default E-mail" field is the e-mail address that should be stored. This field will be automatically populated when sending a new proof. Don't worry, you can always adjust it later. This should be just one e-mail address; you can add additional addresses when sending the proof.</h4>
		
		<h4>The "Default E-Mail Name" is how the client will be greeted in e-mail alerts related to the proofs. For example, "Hi <strong>John</strong>, Your proofs are ready!"</h4> 
		<form action="actions/add_client_mini.php" method="post" class="default_form" id="add-client-mini-form">
		    <ul class="default_form">
		    	<li><label for="client_name">Client Name:</label></li>
		    	<li><input type="name" name="client_name" id="client_name" placeholder="Bubba Gump Shrimp Co."><br /><label for="client_name" class="error"></label></li>
		    	<li><label for="default_email">E-Mail Address:</label><br /></li>
		    	<li><input type="email" name="default_email" id="default_email" placeholder="forest@bgsc.com"><br /><label for="default_email" class="error"></label></li>
		    	<li><label for="default_salutation">Full Name:</label><br /></li>
		    	<li><input type="name" name="default_salutation" id="default_salutation" placeholder="Forrest Gump"><br /><label for="default_salutation" class="error"></label></li>
		    	<li><label for="default_name">First Name:</label></li>
		    	<li><input type="name" name="default_name" id="default_name" placeholder="Forrest"><br /><label for="default_name" class="error"></label></li>
		    	<li class="button"><button type="submit" class="submit">Add Client</button></li>
		    </ul>		    
		</form>
		<a id="add-client-mini-close" class="popup-link" onclick="closePopupBox()">Close</a>
	</div>
	
	<div class="content">
		<h1>Submit a New Proof:</h1>
		<h4>Use this form to submit a new proof and send it to a customer. Select a client from the drop down list to continue sending the proof. If it's a new client, use the popup modal to submit the default details for that customer. The page will automatically refresh and you can select the new one.</h4>
		<form class="default_form" enctype="multipart/form-data" action="actions/add_proof.php" method="post" id="submit-proof">
			<ul class="default_form">
				<li><input type="hidden" id="order_id" name="order_id" value="<?php echo $new_order_id; ?>"><br /></li>
			    <li><label for="client_drop">Client Name:</label></li> 
			    <li> <!-- TODO: change this to searchable text box -->
				    <select class="default_form" name="client_drop" id="client_drop" onChange="changeEmail(this.value)">
					    <option value="" selected>Choose an existing client</option>
					    <?php foreach ($client_array as $row): ?>
					    	<option value="<?php echo $row['id']; ?>"><?php echo htmlentities($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></option>
					    <?php endforeach; ?>
					</select><br /><label for="client_drop" class="error"></label>
					<div id="txtHint"> <!-- use this div as anchor to replace with populated client details -->
						Choose a client to proceed<br />
						<a id="add-client-mini-open" class="popup-link" onClick="loadPopupBox()">Add a new client</a>
					</div>
			    </li>
				<div class="hiddenTail" style="display: none;"> <!-- this will become visible once a client names is selected and populated at #txthint -->
					<?php include 'includes/submit_proof_tail.php'; ?>
				</div>
			</ul>
		</form>
  </div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script><script src="../assets/js/jquery-ui.min.js"><\/script>')</script>
	<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="assets/js/additional-methods.js"></script>
	<script src="assets/js/proof-swap.js"></script>
	<script src="assets/js/submit-proof-ui.js"></script>
	<script src="assets/js/submit-proof-validate.js"></script>
	<script src="assets/js/tooltips.js"></script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
  <script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</body>
</html>