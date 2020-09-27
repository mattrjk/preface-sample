<!-- despite its name, this script is used for gathering all details about an existing client and populating it on the submit proof page -->
<!-- TODO: change file name to be more relevant to what is actually going on here -->

<?php
  //open connection to database
	require("includes/common.php");

	$q = $_REQUEST['q']; //customer ID number from dropdown on form
	
	$email_query = "SELECT * FROM client_list WHERE id = $q";
	
	try {
        $email_stmt = $db->query($email_query);
    }
    
    catch(PDOException $ex) { 
        die("Failed to run client query: " . $ex->getMessage());
    }
    
    while ($u = $email_stmt->fetch(PDO::FETCH_ASSOC)) {
		$default_array[] = $u; //get all details about client and put in array to be iterated below
	}

	foreach($default_array as $row) {
	    echo '<li><label for="send_to">E-mail Address:</label><br /></li>';
		  echo '<li><input type="name" name="send_to" id="send_to" value="' . $row['default_email'] . '"><br /><label for="send_to" class="error"></label></li>';
	    echo '<li><label for="salutation">Full Name:</label><br /></li>';
	    echo '<li><input type="name" id="salutation" name="salutation" value="' . $row['default_salutation'] . '"><br /><label for="salutation" class="error"></label></li>';
	    echo '<li><label for="first_name">First Name:</label></li>';
	    echo '<li><input type="name" name="first_name" id="first_name" value="' . $row['default_name'] . '"><br /><label for="first_name" class="error"></label></li>';
	    echo '<li><label for="cc">CC E-mail Addresses:</label><br /><span class="label-hint">Separate multiple email addresses with commas</span></li>';
	    echo '<li><input type="name" id="cc" name="cc"><br /><label for="cc" class="error"></label></li>';
	    echo '<input type="hidden" name="client_name" id="client_name" value="' . $row['client_name'] .'">';
  }
  
  //TODO: figure out a better way of doing this. See get_closed_proofs.php for similar issue. I don't think this needs to be done in a loop, as there's only one set of contact information associated with each client, as opposed to multiple orders per client in get_closed_proofs.php