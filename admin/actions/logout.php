<!-- this is the script to log an admin user out of the system -->

<?php 

    //open connection to database
    require("includes/common.php"); 
     
    //remove user data from session in browser and redirect to the login page again
    unset($_SESSION['user']); 

    header("Location: ../index.php"); 
    die("Redirecting to: ../index.php");