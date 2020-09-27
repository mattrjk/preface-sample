<!-- query used to create new client details from proof submission page -->

<?php
  //open connection to database and check for logged-in user
	require("../../includes/common.php");

	if(empty($_SESSION['user'])) { 
        header("Location: index.php");
        die("Redirecting to index.php");
	}

    if(!empty($_POST)) {
        //insert new client into client list table
        $query = "INSERT INTO client_list (client_name, default_email, default_name, default_salutation) VALUES (:client_name, :default_email, :default_name, :default_salutation)";

        $query_params = array( 
            ':client_name' => $_POST['client_name'], 
            ':default_email' => $_POST['default_email'],
            ':default_name' => $_POST['default_name'],
            ':default_salutation' => $_POST['default_salutation']
        ); 
         
        try { 
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
        } 
        
        catch(PDOException $ex) {
            die("Failed to run query: " . $ex->getMessage());
        }
        
	}