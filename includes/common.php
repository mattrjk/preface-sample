<!-- This file was stored in a protected folder on the server and was not publicly accessible. This was global for both the client and admin interfaces and is included in this location for ease of access -->

<?php
  //create API keys as environmental variables for reuse
    putenv("AZURE_BLOB_CONNECTION_STRING=DefaultEndpointsProtocol=https;AccountName=XXX;AccountKey=XXX");
    putenv("MAILCHIMP_API_KEY=XXX");
    putenv("MAILCHIMP_API_KEY=XXX");
    putenv("POSTMARK_API_KEY=XXX");
    
    date_default_timezone_set('America/New_York');
    use Postmark\PostmarkClient;

    //The function used when submitting the "your proof is ready" transaction alert email to clients
    function postmarkSendReady($to, $cc, $id, $name, $order_id, $customer_id) {
        $client = new PostmarkClient(getenv('POSTMARK_API_KEY'));
        $message = [
            'To' => $to,
            'Cc' => $cc,
            'Bcc' => "proof-alerts@domain.com",
            'From' => "donotreply@domain.com",
            'TemplateId' => $id,
            'TemplateModel' => [
                "salutation" => $name,
                "order" => $order_id,
                "customer" => $customer_id
            ]
        ];
    
        $sendResult = $client->sendEmailBatch([$message]);
        
        if($sendResult['errorcode'] == '0') {
            return $sendResult['errorcode'];
        }
        
        else {
            return $sendResult['message'];
        }
    }

    // The function used when a client submitted the self-service, non-actionable copy form to send to a third-party
    function postmarkSendCopy($from_name, $from_email, $to_name, $to_email, $id, $order_id, $customer_id) {
        $client = new PostmarkClient(getenv('POSTMARK_API_KEY'));

        $message = [
            'To' => $to_email,
            'From' => "donotreply@domain.com",
            'TemplateId' => $id,
            'ReplyTo' => $from_email,
            'TemplateModel' => [
                "salutation" => $to_name,
                "originator_name" => $from_name,                
                "order" => $order_id,
                "customer" => $customer_id,
                "originator_email" => $from_email
            ]
        ];
    
        $sendResult = $client->sendEmailBatch([$message]);
        
        if($sendResult['errorcode'] == '0') {
            return $sendResult['errorcode'];
        }
        
        else {
            return $sendResult['message'];
        }
    }

    //despite its name, this function is used for sending both approval and changes
    function postmarkSendApproval($to_email, $to_name, $client_name, $description, $status, $changes, $sig, $id, $cc) {
        $client = new PostmarkClient(getenv('POSTMARK_API_KEY'));

        $message = [
            'To' => $to_email,
            'Cc' => $cc,
            'Bcc' => "proof-alerts@domain.com",
            'From' => "donotreply@domain.com",
            'TemplateId' => $id,
            'TemplateModel' => [
                "salutation" => $to_name,
                "client_name" => $client_name,
                "order_description" => $description,
                "approval_status" => $status,
                "changes" => $changes,
                "approver" => $to_name,
                "sig" => $sig
            ]
        ];
    
        $sendResult = $client->sendEmailBatch([$message]);
        
        if($sendResult['errorcode'] == '0') {
            return $sendResult['errorcode'];
        }
        
        else {
            return $sendResult['message'];
        }
    }

    //TODO: combine the above functions into one multi-use function. This will require some research because to push variables to Postmark, you must declare them beforehand. Explore possibility of hidden null variables?

    // Adapted from internet sources. Used for finding the extension of the proof file submitted by design staff. At present, all proofs are PDF format, but it's the goal to support other formats as well, so this was included as a future-proofing reminder
    function findexts ($filename) { 
        $filename = strtolower($filename) ; 
        $exts = split("[/\\.]", $filename) ; 
        $n = count($exts)-1; 
        $exts = $exts[$n]; 
        return $exts; 
    }

    //define MySQL server connections. NB: this file was stored in protected storage
    $host = 'proofs.db';
    $dbname = 'preface';
    $username = 'preface';
    $password = 'XXX';

    // set to UTF8 for non-pure ASCII characters
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    
    //connect to DB
    try { 
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    } 

    catch(PDOException $ex) { 
        die("Failed to connect to the database: " . $ex->getMessage());
    } 

    //set error mode to throw an exception and stop the script. Will be used in future proper error-handling
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    //set to FETCH_ASSOC to get each matching row in an array. Useful for orders that have multiple revisions associated with it.
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    //TODO: don't touch! Possibly breaks/is responsible for automatic downloading of embedded PDFs.
    header('Content-Type: text/html; charset=utf-8');

    //sessions used for admin interface
    session_start();