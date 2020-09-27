//confirmation dialog indicating that the revision status was updated manually

$(function() {
		
	$("#edit-proof-success-dialog").dialog({
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

});