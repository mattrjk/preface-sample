<!-- used for viewing old completed proofs -->

<?php
  //open connection to database and check for logged-in user
	require("includes/common.php");

	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php"); 
	} 
    // get client names and IDs for drop down menu
    $client_query = "SELECT id, client_name FROM client_list ORDER BY client_name";

    try {
        $client_stmt = $db->query($client_query);     
    } 

    catch(PDOException $ex) { 
        die("Failed to run client query: " . $ex->getMessage()); 
    } 
  // assign all client names and IDs to an array
	while ($u = $client_stmt->fetch(PDO::FETCH_ASSOC)) {
		$client_array[] = $u;
	}  
	
?>

<!DOCYTYPE html>
<html>
<head>
	<title>Closed Orders - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="assets/css/layout.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	<div class="content">
		<h1>Completed Proofs</h1>
		<h4>To view old and completed proofs, select a customer name from the dropdown menu. To view revisions and edit the order, click the order number in the table.</h4>
    
    <!-- TODO: make this a searchable dropdown -->
		<select class="default_form" name="client_drop" id="client_drop" onChange="changeEmail(this.value)">
		    <option value="" selected>Choose an existing client</option>
		    <?php foreach ($client_array as $row): ?>
		    	<option value="<?php echo $row['id']; ?>"><?php echo htmlentities($row['client_name'], ENT_QUOTES, 'UTF-8'); ?></option>
		    <?php endforeach; ?>
		</select>
		
		<div id="txtHint">
			Choose a client to proceed
		</div>
  </div>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="assets/js/closed-proofs-swap.js"></script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
	<script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
</body>
</html>