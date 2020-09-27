<!-- This page is what the client would visit to view the entire order if they received the non-actionable copy -->

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

	$customer_id = $_REQUEST['customer_id'];
  $order_id = $_REQUEST['order_id'];
  
  //grab all details for the specific order in question and execute query against MySQL backend
	$proof_query = "SELECT * FROM proofs WHERE ('$order_id' = order_id) ORDER BY revision DESC";

	try { 
    $stmt = $db->prepare($proof_query);
    $stmt->execute();
  } 

  catch(PDOException $ex) { 
    die("Failed to run client query: " . $ex->getMessage());
  }

  //gather all revisions in to one array and assign the most recent revision to its own for ease of use
  $order_info = $stmt->fetchAll();
  $latest_proof = $order_info[0];

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8">
	<title>Order Copy Overview - Preface</title>
	<link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
	<link href="/assets/css/style.css" rel="stylesheet">
	<link href="/assets/css/layout.css" rel="stylesheet">
	<link media="only screen and (max-device-width: 480px)" rel="stylesheet" href="/assets/css/phone.css" type="text/css">
	<link media="only screen and (min-device-width: 541px) and (max-device-width: 800px)" rel="stylesheet" href="/assets/css/tablet.css" type="text/css">
</head>
<body>
	<div class="content" style="margin-top: 25px;">
		<div id="copy_logo" style="width: 100%; margin: 0 auto; text-align: center;">
			<a href="/"><img class="mobile-logo" src="/assets/img/preface_logo_side_whitebg.png"></a>
		</div>
		<div class="job-hint">
			<p>Below you can find information related to your order. Please note the approval status. To submit changes or approval, please contact the person who submitted the order.</p>
		</div>
		<hr class="overview">
			<div class="proof_overview">
				<div class="job_info">
					<table class="job_info">
						<tr>
							<td>Customer Name:</td>
							<td><?php echo $latest_proof['client_name']; ?></td>
						</tr>
						<tr>
							<td>Order Description:</td>
							<td><?php echo $latest_proof['order_description']; ?></td>
						</tr>
					</table>
				</div>
				
				<div class="proof_hint">
					<p>Proof History:</p>
				</div>
				
				<div class="mobile-table-container">
					<table class="proof_overview rounded striped responsive">
						<thead>
							<tr>
								<th>Revision</th>
								<th>Description</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($order_info as $row): ?>
								<tr>
									<td><a href="/cj/<?php echo $row['customer_id']; ?>/<?php echo $row['order_id']; ?>/<?php echo $row['revision']; ?>"><?php echo $row['revision']; ?></a></td>
									<td><?php echo $row['revision_description']; ?></td>
									<td>
										<?php
											if($row['approval_status'] == 0) {echo '<span class="proof_pending">Ready for approval</span>';}
											if($row['approval_status'] == 1) {echo '<span class="proof_approved">Approved</span>';}
											if($row['approval_status'] == 2) {echo '<span class="proof_approved">Approved w/changes</span>';}
											if($row['approval_status'] == 3) {echo '<span class="proof_not_approved">Not approved</span>';}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="view_proof">
					<button type="submit" class="view_proof" onclick='window.location.href = "/cj/<?php echo $latest_proof['customer_id']; ?>/<?php echo $latest_proof['order_id']; ?>/<?php echo $latest_proof['revision']; ?>"'>View Latest Proof</button>
				</div>

		<?php include 'includes/footer.php'; ?>
	</div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script>')</script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="/assets/js/responsive-tables.js"></script>
</body>
</html>
	