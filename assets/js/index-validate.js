// checks if input fields are not empty and if the customer and order IDs are valid through action/check_login.php

$.validator.setDefaults({
	submitHandler: function(form) {
	    form.submit();
	}
});

$().ready(function() {

	$("#loginForm").validate({
		rules: {
			customer_id: {
				required: true,
				remote: {
                    url: "actions/check_login.php",
                    type: "post"
                }
            },
			order_id: {
				required: true,
				remote: {
					url: "actions/check_login.php",
					type: "post"
				}
			}
		},
		messages: {
			customer_id: {
				required: "Please enter your customer ID",
				remote: "That is not a valid customer ID"
			},
			order_id: {
				required: "Please enter your order ID",
				remote: "That is not a valid order ID"
			}
		}
	});
});