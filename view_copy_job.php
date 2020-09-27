<!-- This page is what the client would visit to view the individual revision if they received the non-actionable copy -->

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
	<title>Proof Copy Overview - Preface</title>
	<link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
	<link href="/assets/css/style.css" rel="stylesheet">
	<link href="/assets/css/layout.css" rel="stylesheet">
	<link media="only screen and (max-device-width: 540px)" rel="stylesheet" href="/assets/css/phone.css" type="text/css">
	<link media="only screen and (min-device-width: 541px) and (max-device-width: 800px)" rel="stylesheet" href="/assets/css/tablet.css" type="text/css">
</head>
<body>
	<div class="content" style="margin-top: 25px;">
		<div id="copy_logo" style="width: 100%; margin: 0 auto; text-align: center;">
			<a href="/"><img class="mobile-logo" src="/assets/img/preface_logo_side_whitebg.png"></a>
		</div>
		<div class="job-hint">
			<p>Please view a copy of your proof below. <span class="mobile-hide">If you're running an older or mobile browser, you may not be able to see your proof in the browser window.</span> Click the link to download a copy of your PDF proof instead and view it using an application like Adobe Reader.</p>
		</div>
		<div class="job_info">
			<table class="job_info">
				<tr>
					<td>Client name:</td>
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
		<div class="mobile-media">To view your proof, tap <a href="https://prooffiles.domain.com/<?php echo $proof_info['proof_file']; ?>">here</a> to view the PDF in your browser window or open it in another app on your device.</div>
		<a class="media" href="https://prooffiles.domain.com/<?php echo $proof_info['proof_file']; ?>"></a>
		<?php include 'includes/footer.php'; ?>
	</div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script>')</script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="/assets/js/jquery.media.js"></script>
	<script type="text/javascript" src="/assets/js/jquery.metadata.js"></script>
	<script type="text/javascript" src="/assets/js/job-media.js"></script>
</body>
</html>	