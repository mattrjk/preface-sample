// this dialog only appears when the customer clicks the not approved radio option. We want to remind them that there is a charge for additional revisions that aren't our fault to prevent abuse of revision policy

$(function() {
	$( "#not_approved_dialog" ).dialog({
		autoOpen: false,
		modal: true,
	    closeOnEscape: false,
		dialogClass: "no-close",
	    buttons: {
	        "I Understand": function() {
	            $(this).dialog("close");
	        }
	    }
    });
 
    $("#not_approved").click(function() {
	    $("#not_approved_dialog").dialog("open");
    });
});