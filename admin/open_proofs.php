<!-- main home page when logged in. Displays all current non-approved proofs -->

<?php

  //open connection to database and check for logged-in user
	require("includes/common.php");

	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php");
	} 
  
  //get all open proofs and count them for posterity
  // TODO: research if the COUNT can be done all in one line with the initial query
	$open_proofs_query = "SELECT * FROM proofs WHERE (finalized = 0) ORDER BY client_name ASC";
	$open_proofs_count_query = "SELECT COUNT(order_id) FROM proofs WHERE (finalized = 0)";

	try { 
		$stmt = $db->query($open_proofs_query); 
	} 

	catch(PDOException $ex) { 
		die("Failed to run client query: " . $ex->getMessage()); 
	} 
		
	while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$open_proofs_array[] = $u;
	}

	$count_stmt = $db->query($open_proofs_count_query);    
	$open_proofs_count = $count_stmt->fetchColumn();    
	
?>

<!DOCYTYPE html>
<html>
<head>
	<title>Open Orders - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="assets/css/layout.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	<div class="content">
		<h1>Proofs Out For Approval:</h1>
		<h3>
			There <?php if($open_proofs_count == 1) {echo "is";} else {echo "are";} ?> currently <?php echo $open_proofs_count; ?> open <?php if($open_proofs_count == 1) {echo "proof";} else {echo "proofs";} ?>! 
			<?php if($open_proofs_count > 10) {echo "What a busy day!";} ?>
		</h3>
		<h4 <?php if($open_proofs_count == 0) {echo 'class="no-proofs-hide"';} ?>>To send a revision or manually change the approval status, click the client name.</h4>
		<table class="striped rounded <?php if($open_proofs_count == 0) {echo 'no-proofs-hide';} ?>" style="max-width:1400px;">
			<thead>
			    <tr>
			        <th>Client Name</th>
			        <th>Order Description</th>
			        <th>Latest Proof Sent Out</th>
			        <th>Sent To</th>
			    </tr>
			</thead>
			<tbody>
			    <?php foreach ($open_proofs_array as $row): ?>
			        <tr>
			            <td><a href="edit_proof.php?order_id=<?php echo $row['order_id']; ?>"><?php echo htmlentities($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></a></td>
			            <td><?php echo htmlentities($row['order_description'], ENT_QUOTES, 'UTF-8'); ?></td>
			            <td><?php echo htmlentities($row['created'], ENT_QUOTES, 'UTF-8'); ?></td>
			            <td><?php echo htmlentities($row['sent_to'], ENT_QUOTES, 'UTF-8'); ?></td>
			        </tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
  </div>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
	<script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
</body>
</html>