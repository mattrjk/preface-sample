<!-- The overview page displays all proof revisions for the entire order and also allows clients to send a non-actionable copy to someone else -->

<?php
  //establish database connection and check for defined variables and assign them for query
	require("includes/common.php");
	
	$customer_id = $_REQUEST['customer_id'];
	$order_id = $_REQUEST['order_id'];

	if(empty($_REQUEST['customer_id'])) {
   	header("Location: index.php");
    die("Redirecting to index.php");
 	}

  if(empty($_REQUEST['order_id'])) {
  	header("Location: index.php");
    die("Redirecting to index.php");
  }

  //grab all details for the specific order in question and execute query against MySQL backend and then sort with newest revision first
	$order_query = "SELECT * FROM proofs WHERE ($order_id = order_id) ORDER BY revision DESC";

	try { 
    $stmt = $db->prepare($order_query);
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
	<title>Order Overview - Preface</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
	<link href="/assets/css/style.css" rel="stylesheet">
	<link href="/assets/css/layout.css" rel="stylesheet">
	<link media="only screen and (max-device-width: 480px)" rel="stylesheet" href="/assets/css/phone.css" type="text/css">
	<link media="only screen and (min-device-width: 541px) and (max-device-width: 800px)" rel="stylesheet" href="/assets/css/tablet.css" type="text/css">
	<!--[if IE 8]><link href="/assets/css/ie8.css" rel="stylesheet"><![endif]-->
	<!--[if IE 9]><link href="/assets/css/ie9.css" rel="stylesheet"><![endif]-->
	<!--[if !IE]><!--><script type="text/javascript" src="/assets/js/ie10css.js"></script><!--<![endif]--> 
	<!-- JQuery calls -->
	<link href="/assets/css/jquery-ui.theme.css" rel="stylesheet">
	<link href="/assets/css/jquery-ui.structure.css" rel="stylesheet">
</head>
<body>
	<div id="container">
		<?php include 'includes/header.php'; ?>
		<div id="contentLayer"></div>
		<div id="content">
			<div class="proof_hint">
				<p>Below you can find information related to your order. Please note the approval status. If you need to submit approval or view the proof, please select the approval button below to view the latest proof. To view an earlier revision, click the proof version number in the table.</p>
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
						<tbody> <!-- loop through all revisions and display as table rows -->
							<?php foreach ($order_info as $row): ?>
								<tr>
									<td><a href="/j/<?php echo $row['customer_id']; ?>/<?php echo $row['order_id']; ?>/<?php echo $row['revision']; ?>"><?php echo $row['revision']; ?></a></td>
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
					<button type="submit" class="view_proof" onclick='window.location.href = "/j/<?php echo $latest_proof['customer_id']; ?>/<?php echo $latest_proof['order_id']; ?>/<?php echo $latest_proof['revision']; ?>"'>View Latest Proof</button>
				</div>
				<hr class="overview">
				<div class="send-copy">
					<p>Need to share this with a friend or colleague? Feel free to forward along the initial email we sent you or send a non-actionable copy of the latest revision with this form below.</p>
					<form action="/actions/send_copy.php" id="sendCopy" method="post" enctype="application/x-www-form-urlencoded">
						<input type="hidden" name="customer_id" id="customer_id" value="<?php echo $latest_proof['customer_id']; ?>">
						<input type="hidden" name="order_id" id="order_id" value="<?php echo $_REQUEST['order_id']; ?>">
						<input type="hidden" name="revision" id="revision" value="<?php echo $latest_proof['revision']; ?>">
						<ul class="send-copy">
							<li class="send-copy">
								<ul class="send-copy-interior">
									<li><label for="copy_name">Your friend's name:</label></li>
									<li><input type="name" name="copy_name" id="copy_name" placeholder="Aunt Edna" tabindex="1"><br /><label for="copy_name" class="error"></label></li>
								</ul>
							</li>
							<li class="send-copy">
								<ul class="send-copy-interior">
									<li><label for="originator_name">Your name:</label></li>
									<li><input type="name" name="originator_name" id="originator_name" value="<?php echo $latest_proof['fullname'];?>" tabindex="3"><br /><label for="originator_name" class="error"></label></li>
								</ul>
							</li>
							<li class="send-copy">
								<ul class="send-copy-interior">
									<li><label for="copy_email">Your friend's email:</label></li>
									<li><input type="email" name="copy_email" id="copy_email" placeholder="edna@theairport.com" tabindex="2"><br /><label for="copy_email" class="error"></label></li>
								</ul>
							</li>
							<li class="send-copy">
								<ul class="send-copy-interior">
									<li><label for="originator_email">Your email:</label></li>
									<li><input type="email" name="originator_email" id="originator_email" value="<?php echo $latest_proof['sent_to'];?>" tabindex="4"><br /><label for="originator_email" class="error"></label></li>
								</ul>
							</li>
						</ul>
						<button type="submit" class="send_copy" style="margin-top:20px;">Send A Copy</button>				
					</form>
				</div>
			</div>
			<?php include 'includes/footer.php' ?>
		</div>
	</div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script>window.jQuery || document.write('<script src="/assets/js/jquery.min.js"><\/script><script src="/assets/js/jquery-ui.min.js"><\/script>')</script>
	<script type="text/javascript" src="/assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="/assets/js/additional-methods.js"></script>
	<script type="text/javascript" src="/assets/js/overview-validate.js"></script>
	<script type="text/javascript" src="/assets/js/navburger.js"></script>
	<script type="text/javascript" src="/assets/js/responsive-tables.js"></script>
	<script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</body>
</html>