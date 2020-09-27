$(function() {
	//dialog for successfully adding a new client on the submit proof page
	$("#add-client-mini-success-dialog").dialog({
		autoOpen: false,
		modal: true,
	    closeOnEscape: false,
		dialogClass: "no-close",
	    buttons: {
	        "Okay": function() {
	            $(this).dialog("close");
	            top.location.reload();
	        }
	    }
    });
    
    //confirmation dialog before submitting new proof to client
    $("#submit-proof-confirm-dialog").dialog({
		autoOpen: false,
		modal: true,
	    closeOnEscape: false,
		dialogClass: "no-close",
	    buttons: {
	        "Cancel": function() {
	            $(this).dialog("close");
	        },
	        "Submit": function() {
		        $("#submit-proof").get(0).submit();
		    }
	    }
    });

});