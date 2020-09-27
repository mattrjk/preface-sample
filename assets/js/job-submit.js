// validating form entries on individual proof revision review page

$.validator.setDefaults({
	submitHandler: function() {
	    $('#confirm-submit-dialog').dialog("open");
    }
});

$().ready(function() {

	$("#submitChanges").validate({
		rules: {
			name: {
				required: true
            },
			client_email: {
				required: true,
				email: true
			},
			changes: { //changes must be filled in if the customer has selected either of the radio options indicating changes
				required: function() {
					if (document.getElementById('approved_pending').checked) {
						return true;
					}
					
					if (document.getElementById('not_approved').checked) {
						return true;
					}
					
					else {
						return false;
					}
				}
			},
			approval: {
				required: true
			}
		},
		messages: {
			name: {
				required: "Please enter your name"
			},
			client_email: {
				required: "Please enter your email",
				email: "Please enter a valid email address"
			},
			changes: {
				required: "Please enter your changes"
			},
			approval: {
				required: "Please select an approval or change option"
			}
		}
	});
	
	$(function() {
		$("#confirm-submit-dialog").dialog({
		        autoOpen: false,
		        modal: true,
		        closeOnEscape: true,
		        dialogClass: "no-close",
				buttons: [
					{
						id: "submit-button",
						name: "submit-button",
						text: "Submit Now",
						click: function() { //disable submit button once clicked the first time for the impatient customers
							$("#submit-button").text("Please Wait...").attr("disabled","disabled");
							$("#submitChanges").get(0).submit();
							return false;
						}
					},
					{
						id: "cancel-button",
						name: "cancel-button",
						text: "Cancel",
						click: function() {
							$(this).dialog("close");
						}
					}
				]
	    });
	});


});
