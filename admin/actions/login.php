<!-- the login script for admin users. Adapted from internet source -->
<!-- TODO: more secure auth method. SSO with AAD? This is a temporary stopgap ONLY -->

<?php 
    //open connection to database
    require("includes/common.php"); 

    $submitted_username = ''; // initialize submitted username now so it can be pre-populated when returning to login form if admin user enters password incorrectly

    if(!empty($_POST)) {
      // get all information about user in preparation for setting the session
		$query = "SELECT id, username, psword, salt, email, first_name, last_name, full_name FROM admin_users WHERE username = :username"; 

        $query_params = array( 
            ':username' => $_POST['username'] 
        ); 
         
        try { 
  	        $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        }
        
        catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage()); 
        } 
         
        $login_ok = false; //initialize now to be flipped later
        $row = $stmt->fetch();
        
        if($row) { //if false, then user does not exist as nothing was able to be retrieved from database

            $check_password = hash('sha256', $_POST['password'] . $row['salt']); //hash submitted password with salt retrieved from database to be checked
            
            for($round = 0; $round < 65536; $round++) { //
                $check_password = hash('sha256', $check_password . $row['salt']); //hash the already hashed password with the salt again 65536 times, to match the registration procedure
            } 
             
            if($check_password === $row['psword']) { //if the above matches what's in the database, then the password submitted matches what's on file and the user should be granted access
                $login_ok = true; 
            } 
        } 

        if($login_ok) { //if true then user exists and password was correct and we can add the user to the session and let them in to the restricted data
            unset($row['salt']); 
            unset($row['psword']);

            $_SESSION['user'] = $row; 

            header("Location: ../open_proofs.php"); 
            die("Redirecting to: ../open_proofs.php"); 
        }
        
        else {
          include '../includes/login_failed_alert.php';
          $submitted_username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8'); //prepopulate username on index page in case user entered password incorrectly, for ease of trying to log in again
        } 
    } 