//popup dialog confirming desire to send revised proof to client

$(function() { 
    $("#submit-revision-confirm-dialog").dialog({
		autoOpen: false,
		modal: true,
	    closeOnEscape: false,
		dialogClass: "no-close",
	    buttons: {
	        "Cancel": function() {
	            $(this).dialog("close");
	        },
	        "Submit": function() {
		        $("#submit-revision").get(0).submit();
		    }
	    }
    });
});