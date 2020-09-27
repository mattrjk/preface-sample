<!-- The "job" page is to view the individual proof revision -->

<?php
  //establish database connection and check for defined variables and assign them for query
	require("includes/common.php");
	
	if(empty($_REQUEST['customer_id'])) {
	   	header("Location: index.php");
        die("Redirecting to index.php");
 	}

  if(empty($_REQUEST['order_id'])) {
  	header("Location: index.php");
    die("Redirecting to index.php");
  }
  
  if(empty($_REQUEST['revision'])) {
    header("Location: index.php");
    die("Redirecting to index.php");
  }

	$customer_id = $_REQUEST['customer_id'];
	$order_id = $_REQUEST['order_id'];
	$revision = $_REQUEST['revision'];

  //grab all details for the specific revision in question and execute query against MySQL backend
	$proof_query = "SELECT * FROM proofs WHERE ('$order_id' = order_id AND '$revision' = revision)";

	try { 
    $stmt = $db->prepare($proof_query); 
    $stmt->execute();    
  }

  catch(PDOException $ex) { 
    die("Failed to run client query: " . $ex->getMessage()); 
  }

  //TODO: figure out a way to combine this into one
  $rows = $stmt->fetchAll();
  $proof_info = $rows[0];

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8">
	<title>Proof Overview - Preface</title>
	<link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
	<link href="/assets/css/style.css" rel="stylesheet">
	<link href="/assets/css/layout.css" rel="stylesheet">
	<link href="/assets/css/jquery.signaturepad.css" rel="stylesheet">
	<link media="only screen and (max-device-width: 540px)" rel="stylesheet" href="/assets/css/phone.css" type="text/css">
	<link media="only screen and (min-device-width: 541px) and (max-device-width: 800px)" rel="stylesheet" href="/assets/css/tablet.css" type="text/css">
	<!--[if !IE]><!--><script type="text/javascript" src="/assets/js/ie10css.js"></script><!--<![endif]-->  
	<link href="/assets/css/jquery-ui.theme.css" rel="stylesheet">
	<link href="/assets/css/jquery-ui.structure.css" rel="stylesheet">
	<!--[if lt IE 9]><script src="//assets/js/flashcanvas.js"></script><![endif]-->
	<!--[if IE 8]><link href="/assets/css/ie8.css" rel="stylesheet"><![endif]-->
	<!--[if IE 9]><link href="/assets/css/ie9.css" rel="stylesheet"><![endif]-->
</head>
<body>
	<div id="container">
		<?php include 'includes/header.php'; ?>
		<div id="contentLayer" style="min-height: 1886px;"></div>		
		<div id="content">
			<div class="job-hint">
				<p>Please review your proof below. <span class="mobile-hide">If you're using an incompatible browser or don't see your proof in the space below, download a PDF copy of your proof by clicking the download link.</span> Please note that proofs <span class="emphasize-it">MAY NOT</span> be to scale and that there may be more than one page of proofs in the document. Use your browser or PDF viewer to zoom in to view details.</p>
			</div>
			<div class="job_info">
				<table class="job_info">
					<tr>
						<td>Customer name:</td>
						<td><?php echo $proof_info['client_name']; ?></td>
					</tr>
					<tr>
						<td>Order description:</td>
						<td><?php echo $proof_info['order_description']; ?></td>
					</tr>
					<tr>
						<td>Proof version:</td>
						<td><?php echo $proof_info['revision']; ?></td>
					</tr>
					<tr>
						<td>Revision description:</td>
						<td><?php echo $proof_info['revision_description']; ?></td>
					</tr>
					<tr class="mobile-hide">
						<td>Proof file:</td> 
						<td><a href="https://prooffiles.domain.com/<?php echo $proof_info['proof_file']; ?>">Click here</a> to download a copy</td>					
					</tr>
				</table>
			</div>
			<div class="mobile-media ie8-media">To view your proof, follow <a href="https://prooffiles.domain.com/<?php echo $proof_info['proof_file']; ?>">this link</a> to view the PDF in your browser window or open it in another app on your device.</div>
			<div class="media mobile-hide">
				<iframe class="media" src="https://prooffiles.domain.com/<?php echo $proof_info['proof_file']; ?>"></iframe>
			</div>
			<div id="already-approved" class="job_info <?php if($proof_info['approval_status'] == 0){echo 'no-show';} else{echo 'must-show';} ?>">You've already submitted approval for this proof. If you submitted this in error, please contact the person you originally placed your order with or send us an email at <a href="mailto:sales@domain.com">sales@domain.com</a>.</div>
			<div class="job-hint">
				<p>Your first proof is free, and any changes requiring an additional proof that were not the result of our error may result in a $10 art change fee. Any additional proofs may cause a delay in production. If any errors are discovered after you give approval, you are responsible for up to 50% of the remake cost.</p>
				<p>Please <span class="emphasize-it">do not call, fax, or email</span> with any edits -- changes must be included in the below box in a list format. When you're ready to submit your approval, click the button below to sign and submit your digital signature.</p>
			</div>
			<div class="approval">
				<form action="/actions/submit_changes.php" method="post" class="approval sigPad" id="submitChanges">
					<div class="approval_element">
						<label for="approval" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>This proof is... (please choose):</label><br />
						<label for="approved_as_is" class="approval_radio <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>">
							<input type="radio" name="approval" id="approved_as_is" value="approved_as_is" <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>>Approved as is.
						</label><br />
						<label for="approved_pending" class="approval_radio <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>">
							<input type="radio" name="approval" id="approved_pending" value="approved_pending" <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>>Approved with the following changes. I don't need a new proof.
						</label><br />
						<label for="not_approved" class="approval_radio <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>">
							<input type="radio" name="approval" id="not_approved" value="not_approved" <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>>Not approved. Please send a new proof with these changes.
						</label><br />
						<label for="approval" class="error"></label>
					</div>
					<div class="approval_element">
						<label for="changes" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>List your changes:</label><br />
						<textarea <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?> name="changes" id="changes" placeholder="If you need to submit changes, please send them in a list format. For example, 'Please capitalize successful on line three.' Please do not recreate the layout as we will have to manually find your changes and may miss something. If you need to provide us with a new logo or other file, send it to the person you originally placed the order with and just reference it here." <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>></textarea><br />
						<label for="changes" class="error"></label>
					</div>
					<div class="approval_element clearfix">
						<ul class="approval-contact">
							<li class="approval-contact">
								<ul class="approval-contact-interior">
									<li><label for="name" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>Your name:</label></li>
									<li><input <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?> type="text" name="name" id="name" class="name" value="<?php echo $proof_info['fullname']; ?>" required <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>><br /><label for="name" class="error"></label></li>
								</ul>
							</li>
							<li class="approval-contact">
								<ul class="approval-contact-interior">
									<li><label for="originator_name" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>Your email address:</label></li>
									<li><input <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?> type="email" name="client_email" id="client_email" value="<?php echo $proof_info['sent_to']; ?>" required <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>><br /><label for="client_email" class="error"></label></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class="approval_element" style="text-align: center;">
						<label for="join_mailer" class="positive_action <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>"><input type="checkbox" id="join_mailer" name="join_mailer" style="margin: 25px 5px 25px auto;" <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>>Yes, add me to the mailing list</label>
					</div>
					<div class="sigPad approval_element mobile-hide">
						<label for="signature_pad" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>Draw your signature:</label>
					   <div class="sig sigWrapper">
							<canvas class="pad" width="600" height="125"></canvas>
					   </div>
					</div>
					<div class="sigPadMobile approval_element mobile-show">
						<label for="signature_pad" <?php if($proof_info['approval_status'] != 0){echo 'class="gray"';} ?>>Draw your signature:</label>
					   <div class="sig sigWrapper">
							<canvas class="pad" width="300" height="100"></canvas>
					   </div>
					   <div class="clear_signature mobile-show">
							<ul class="sigNav">
								<li class="clearButton">
									<a href="#clear" class="negative_action <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>">Clear Signature</a>
								</li>
							</ul>
					   </div>
					</div>
					
				    <input type="hidden" name="output" class="output" id="output">
					<input type="hidden" name="order_id" id="order_id" value="<?php echo $order_id; ?>">
					<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer_id; ?>">
					<input type="hidden" name="client_name" id="client_name" value="<?php echo $proof_info['client_name']; ?>">
					<input type="hidden" name="order_description" id="order_description" value="<?php echo $proof_info['order_description']; ?>">
					<input type="hidden" name="revision" id="revision" value="<?php echo $proof_info['revision']; ?>">
					<input type="hidden" name="cc" id="cc" value="<?php echo $proof_info['cc']; ?>">
					
					<div class="approval_input clearfix">
						<div class="clear_signature mobile-hide">
							<ul class="sigNav">
								<li class="clearButton">
									<a href="#clear" class="negative_action <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>">Clear Signature</a>
								</li>
							</ul>
						</div>
						<div class="approval_button">
							<button type="submit" class="approval <?php if($proof_info['approval_status'] != 0){echo 'gray';} ?>" <?php if($proof_info['approval_status'] != 0){echo 'disabled="disabled"';} ?>>Submit</button>
						</div>
					</div>
				</form>
			</div>
			<?php include 'includes/footer.php'; ?>
		</div>
		<div id="not_approved_dialog" title="Artwork Not Approved">
			<p>Remember that only the first proof is free. There may be a $10 art change fee if you need to see a new proof for a change that isn't the result of our mistake. Any additional proofs may cause a delay in production.</p>
		</div>
		<div id="confirm-submit-dialog" title="Approval Submission">
			<p>You are now submitting your proof! If you're approving, we'll begin production on your order. Otherwise, you can generally expect a new proof from us within one business day. By submitting your approval, you're acknowledging that you've read and agreed to the terms outlined above. Only click the submit button once.</p>
		</div>
	</div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script><script src="..//assets/js/jquery-ui.min.js"><\/script>')</script>
  <!-- Jquery modules -->
	<script type="text/javascript" src="/assets/js/jquery.media.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.metadata.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.signaturepad.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="/assets/js/additional-methods.js"></script>
	
	<!-- Page-specific js -->
	<script type="text/javascript" src="/assets/js/json2.min.js"></script>
	<script type="text/javascript" src="/assets/js/job-sigpad.js"></script>
	<script type="text/javascript" src="/assets/js/job-submit.js"></script>
	<script type="text/javascript" src="/assets/js/job-ui.js"></script>
	<script type="text/javascript" src="/assets/js/navburger.js"></script>
	<script type="text/javascript" src="/assets/js/responsive-tables.js"></script>

	<script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</body>
</html>
	