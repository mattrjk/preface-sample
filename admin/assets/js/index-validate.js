//form validation of admin login page

$.validator.setDefaults({
	submitHandler: function(form) {
	    form.submit();
	}
});

$().ready(function() {

	$("#login_form").validate({
		rules: {
			username: {
				required: true,
				email: true
            },
			password: {
				required: true
			}
		},
		messages: {
			username: {
				required: "Please enter your username",
				email: "Please enter a valid username"
			},
			password: {
				required: "Please enter your password"
			}
		}
	});

});