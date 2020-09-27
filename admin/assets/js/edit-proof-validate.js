//validation of form data for revision submission

$().ready(function() {
	$("#submit-revision").validate({
		submitHandler: function(form) {
			$("#submit-revision-confirm-dialog").dialog("open");
		},
		rules: {
			revision_description: {
				required: true
			},
			proof_file: {
				required: true,
				extension: "pdf"
			}
		},
		messages: {
			revision_description: {
				required: "Please enter a description of these revisions"
			},
			proof_file: {
				required: "Please select a file for uploading",
				extension: "Please select only a PDF file"
			}
		}
	});
});