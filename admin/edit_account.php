<!-- page to edit currently-logged in account details -->

<?php
	//open connection to database and check for logged-in user
	require("../includes/common.php");

	if(empty($_SESSION['user'])) {
		header("Location: index.php"); 
		die("Redirecting to index.php"); 
	} 

    if(!empty($_POST)) {

        if(!empty($_POST['password'])) { //hash new password if changing
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
            $password = hash('sha256', $_POST['password'] . $salt);
            
            for($round = 0; $round < 65536; $round++) { 
                $password = hash('sha256', $password . $salt);
            } 
        } 
        
        else { 
            $password = null; 
            $salt = null; 
        } 
 
        $query_params = array(
        	':user_id' => $_SESSION['user']['id'],
        	':username' => $_POST['email'],
            ':email' => $_POST['email'],
            ':email_hash' => sha1($_POST['email']),
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':full_name' => $_POST['first_name'] . " " . $_POST['last_name']
        ); 

        if($password !== null) { 
            $query_params[':password'] = $password; 
            $query_params[':salt'] = $salt;
        } 

        $query = "UPDATE admin_users SET username = :username, email = :email, email_hash = :email_hash, first_name = :first_name, last_name = :last_name, full_name = :full_name"; 

        if($password !== null) { 
            $query .= ", psword = :password, salt = :salt"; 
        } 

        $query .= " WHERE id = :user_id"; 
         
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        }
         
        catch(PDOException $ex) { 
            die("Failed to run query: " . $ex->getMessage()); 
        } 

        $_SESSION['user']['email'] = $_POST['email']; 

        header("Location: edit_account_successful.php"); 
        die("Redirecting to edit_account_successful.php");

        //TODO: implement new auth method as per register.php
    } 
     
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin Login - Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="assets/css/layout.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
	<?php include 'includes/header.php'; ?>
	<?php include 'includes/sidebar.php'; ?>
	<div class="content">
		<h1>Edit Your Account Information</h1>
		<h3>Use the form below to input updated information for your account. Leave the password field blank if you don't wish to change your password. You'll need to log out and log in to see these changes.</h3>
		<h4><label class="default_form">Current Username:</label>&nbsp;<?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></h4>
		<form action="edit_account.php" method="post" class="default_form" id="edit_account">
		    <ul class="default_form">
			    <li><label>First Name:</label></li>
			    <li><input type="text" name="first_name" value="<?php echo htmlentities($_SESSION['user']['first_name'], ENT_QUOTES, 'UTF-8'); ?>" /><br><label for="first_name" class="error"></label></li>
				<li><label>Last Name:</label></li>
			    <li><input type="text" name="last_name" value="<?php echo htmlentities($_SESSION['user']['last_name'], ENT_QUOTES, 'UTF-8'); ?>" /><br><label for="last_name" class="error"></label></li>
			    <li><label for="email">E-Mail Address:</label></li>
			    <li><input type="text" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" /><br><label for="email" class="error"></label></li>
			    <li><label for="password">Password:</label></li>
			    <li><input type="password" name="password" value="" /><label for="password" class="error"></label></li>
				<li><p class="default_form_hint">(leave blank if you do not want to change your password)</p></li>
				<li><button type="submit" class="submit">Submit Changes</button></li>
		    </ul>
		</form>
  </div>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>')</script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
  <script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
  <script type="text/javascript" src="assets/js/edit-account-validate.js"></script>
</body>
</html>