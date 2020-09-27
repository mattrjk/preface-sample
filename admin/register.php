<!-- this page is for adding a new admin user -->

<?php 
  //open connection to database and check for logged-in user
    require("includes/common.php");

    if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php"); 
	} 

  // password auth adapted from internet source. Definitely not the most secure
  // TODO: new password auth method. SSO with AAD?
  // TODO: should probably move this to a separate action php like other form actions
    if(!empty($_POST)) { 

        $query = "INSERT INTO admin_users (username, psword, salt, email, email_hash, first_name, last_name, full_name) VALUES (:username, :psword, :salt, :email, :email_hash, :first_name, :last_name, :full_name)"; 

        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 

        $password = hash('sha256', $_POST['password'] . $salt);
        
        $email_hash = sha1($_POST['username']);

        for($round = 0; $round < 65536; $round++) { 
            $password = hash('sha256', $password . $salt);
        }
        
        $full_name = $_POST['first_name'] . " " . $_POST['last_name'];

        $query_params = array( 
            ':username' => $_POST['username'], 
            ':psword' => $password, 
            ':salt' => $salt, 
            ':email' => $_POST['username'],
            ':email_hash' => $email_hash,
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':full_name' => $full_name
        ); 
         
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        
        catch(PDOException $ex) { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        header("Location: register_successful.php"); 
        die("Redirecting to register_successful.php"); 
    } 
     
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add New Administrator - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="assets/css/layout.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	<div class="content">
		<h1>Create A New Admin</h1>
		<h3>Add a new administrative user here. This user will be able to send proofs to clients.</h3>
		<form action="register.php" method="post" class="default_form" id="register_form">
			<ul class="default_form">
				<li><label for="username">Username:</label></li>
				<li><input type="name" name="username" id="username"><br><label for="username" class="error"></label></li>
				<li><label for="first_name">First Name:</label></li>
				<li><input type="name" name="first_name" id="first_name"><br><label for="first_name" class="error"></label></li>
				<li><label for="last_name">Last Name:</label></li>
				<li><input type="name" name="last_name" id="last_name"><br><label for="last_name" class="error"></label></li>
				<li><label for="password">Password:</label></li>
				<li><input type="password" name="password" id="password"><br><label for="password" class="error"></label></li> 
				<li><button type="submit" class="submit">Add New Admin</button></li>
			</ul>
			<input type="hidden" name="email_hash" id="email_hash">
		</form>
  </div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>')</script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
	<script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
  <script type="text/javascript" src="assets/js/jquery.validate.js"></script>
  <script type="text/javascript" src="assets/js/register-validate.js"></script>
</body>
</html>