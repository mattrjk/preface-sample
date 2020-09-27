// validating form for non-actionable copy send to third-party

$.validator.setDefaults({
			submitHandler: function(form) {
			    form.submit();
			}
		});
		
		$().ready(function() {
		
			$("#sendCopy").validate({
				rules: {
					copy_name: {
						required: true
                    },
					originator_name: {
						required: true
					},
					copy_email: {
						required: true,
						email: true
                    },
					originator_email: {
						required: true,
						email: true
					}
				},
				messages: {
					copy_name: {
						required: "Please enter a name to send to"
					},
					originator_name: {
						required: "Please enter your name"
					},
					copy_email: {
						required: "Please enter an email to send to",
						email: "Please enter a valid email address"
					},
					originator_email: {
						required: "Please enter your email",
						email: "Please enter a valid email address"
					}
				}
			});

		});