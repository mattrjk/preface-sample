<!-- reserved for future use. I want to put some fun stats here, like number of proofs completed, etc-->

<?php 
  //open connection to database and check for logged-in user
	require("includes/common.php"); 
	
	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php");
	} 

?> 

<!DOCTYPE html>
<html>
<head>
	<title>Admin Index - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link rel="stylesheet" href="assets/css/layout.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	<div class="content">
		<h1>Welcome to Preface!</h1>
		<h4>Use the menu to the left for navigation. To add new customers or submit new proofs, use the links in the sidebar. For extra actions, click your account photo in the top right corner to display a dropdown menu.</h4>
  </div>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
	<script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
</body>
</html>