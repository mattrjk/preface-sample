<!-- this script is used to populate the table of closed proofs for a particular aclient -->

<?php
  //open connection to database
	require("includes/common.php");

	$q = $_REQUEST['q']; //the q var is the client ID provided in the dropdown menu from the form
	
	$proofs_query = "SELECT order_id, client_name, order_description, MAX(created) AS maxcreated, sent_by, sent_to FROM proofs WHERE (customer_id = $q AND finalized= '1') GROUP BY order_id ORDER BY order_id DESC"; //get all orders from that customer that have been marked as done and present them newest first
	
	
	try {
        $proofs_stmt = $db->query($proofs_query);
    }
    
    catch(PDOException $ex) { 
        die("Failed to run client query: " . $ex->getMessage());
    }
    
    while ($u = $proofs_stmt->fetch(PDO::FETCH_ASSOC)) {
		$proofs_array[] = $u;
	}
	
	echo '<br /><br /><table class="striped rounded">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Order Number</th>';
	echo '<th>Client Name</th>';
	echo '<th>Order Description</th>';
	echo '<th>Last Proof Sent Out</th>';
	echo '<th>Sent By</th>';
	echo '<th>Sent To</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';

	foreach($proofs_array as $row) {
		echo '<tr>';
		echo '<td><a href="edit_proof.php?order_id=' . $row['order_id'] . '">' . $row['order_id'] . '</a></td>';
		echo '<td>' . $row['client_name'] . '</td>';
		echo '<td>' . $row['order_description'] . '</td>';
		echo '<td>' . $row['maxcreated'] . '</td>';
		echo '<td>' . $row['sent_by'] . '</td>';
		echo '<td>' . $row['sent_to'] . '</td>';
		echo '</tr>';
	}
	
	echo '</tbody>';
  echo '</table>';
  
  //TODO: do this some other way! I'm not sure how yet as the closed proofs page will be blank when loading, but I want to update it without reloading the full page...just refresh it using closed-proofs-swap.js at the #txthint div