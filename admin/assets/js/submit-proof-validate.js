$().ready(function() {
  //this script splits multiple submitted emails from the CC field so that they can be individually validated below
	jQuery.validator.addMethod("multiemail", function(value, element) {
	    if (this.optional(element)) 
	        return true;
	    var emails = value.split(/[;,]+/);
	    valid = true;
	    for (var i in emails) {
	        value = emails[i];
	        valid = valid && jQuery.validator.methods.email.call(this, $.trim(value), element);
	    }
	    return valid;
	    },
	
	    jQuery.validator.messages.email
	);
  
  //validating the add client popup form details
	$("#add-client-mini-form").validate({
		submitHandler: function(form) {
			var data = $('#add-client-mini-form').serialize();
		    $.post(
		    	'actions/add_client_mini.php',
		    	data,
		    	function(data) {
	                $("#add-client-mini-success-dialog").dialog("open");
	                $('#add-client-mini').fadeOut("fast");
	            }
		    );
		    
		},
		rules: {
			client_name: {
				required: true,
				remote:	{
	                url: "actions/check_client.php",
	                type: "post"
	            }
	        },
			default_email: {
				required: true,
				multiemail: true //check above!
			},
			default_salutation: {
				required: true
			},
			default_name: {
				required: true
			}
		},
		messages: {
			client_name: {
				required: "Please enter a client name",
				remote: "That client name is already in use"
			},
			default_email: {
				required: "Please enter an email address",
				multiemail: "Please make sure all the email addresses are valid"
			},
			default_salutation: {
				required: "Please enter the default email address contact names"
			},
			default_name: {
				required: "Please enter the default email greeting name"
			}
		}
	});
  
  //validating the full submit proof form details
	$("#submit-proof").validate({
		submitHandler: function(form) {
			$("#submit-proof-confirm-dialog").dialog("open");
		},
		rules: {
			client_drop: {
				required: true
			},
			send_to: {
				required: true,
				multiemail: true
			},
			salutation: {
				required: true
			},
			order_description: {
				required: true
			},
			proof_file: {
				required: true,
				extension: "pdf"
			}
		},
		messages: {
			client_drop: {
				required: "Please choose a client to send the proof to"
			},
			send_to: {
				required: "Please enter an email to send the proof to",
				multiemail: "Please make sure all the email addresses are valid"
			},
			salutation: {
				required: "Please enter the email address contact names"
			},
			first_name: {
				required: "Please enter the email greeting name"
			},
			order_description: {
				required: "Please enter a description for the proofs"
			},
			proof_file: {
				required: "Please select a file to upload",
				extension: "Please choose only a PDF file to upload"
			}
		}
	});
});