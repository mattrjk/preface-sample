//validation of form information to edit admin account details

$.validator.setDefaults({
  submitHandler: function(form) {
      form.submit();
  }
});

$().ready(function() {

  $("#edit_account").validate({
    rules: {
      first_name: {
        required: true
              },
              last_name: {
                required: true
              },
              email: {
                required: true,
                email: true,
                remote:	{
                  url: "actions/check_email.php",
                  type: "post"
                }
              },
      password: {
        required: false
      }
    },
    messages: {
      first_name: {
        required: "Please enter your first name"
      },
      last_name: {
        required: "Please enter your last name"
      },
      email: {
        required: "Please enter your email address",
        email: "Please enter a valid email address",
        remote: "That username/email is currently in use"
      }
    }
  });

});