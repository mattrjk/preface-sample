# Client Interface Includes Folder Files

Below is a list of the description and use-case for each file in this folder. The Includes were used to populate various pages based on what action the client was doing and whether or not it went through successfully.

### approved_successful.php
When the client selected "Approved as is" from the radio button options and was successfully transmitted 

### changes_requested_successful.php
When the client selected "Not approved" from the radio button options and was successfully transmitted

### common.php
Includes frequently reused code for database connections and Postmark email sending

### footer.php
Contains copyright information and version number

### header.php
Contains the fixed navigation menu

### is_sent_successful.php
When the client successfully submitted the form to send a non-actionable copy to a third party

### is_sent_unsuccessful.php
When the client unsuccessfully submitted the form to send a non-actionable copy to a third party. The attempted outgoing email is included. If it was another error, this logging had yet to be implemented as per general project README

### pending_changes_successful.php
When the client selected "Approved with changes" from the radio button options and was successfully transmitted

### submit_changes_footer.php
Closing tags for submit_changes.php action page (see file for comments)

### submit_changes_header.php
Opening tags for submit_changes.php action page (see file for comments)

### submit_changes_unsuccessful.php
A generic error for when the approval/change form did not transmit successfully. In depth logging had yet to be implemented as per general project README

### subscribe_successful.php
A small blurb thanking the client for subscribing to our newsletter 