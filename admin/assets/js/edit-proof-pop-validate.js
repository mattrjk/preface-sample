//validating the form for manually changing a revision's status

$().ready(function() {
	
	$("#set-status-manual-form").validate({
		submitHandler: function(form) {
			var data = $('#set-status-manual-form').serialize();
		    $.post(
		    	'actions/edit_status.php',
		    	data,
		    	function(data) {
	                $("#edit-proof-success-dialog").dialog("open");
	                var tmp=window.open(params);
					tmp.close();
	            }
		    );
		    
		},
		rules: {
			manual_status: {
				required: true
	        }
		},
		messages: {
			manual_status: {
				required: "Please choose a new proof approval status"
			}
		}
	});
	
});