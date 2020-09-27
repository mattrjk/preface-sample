//validation for new admin user registration form

$.validator.setDefaults({
  submitHandler: function(form) {
      form.submit();
  }
});

$().ready(function() {

  $("#register_form").validate({
    rules: {
      username: {
        required: true,
        remote: {
          url: "actions/check_admin.php",
          type: "post"
        },
        email: true
      },
      first_name: {
        required: true
              },
              last_name: {
                required: true
              },
      password: {
        required: true
      }
    },
    messages: {
      username: {
        required: "Please enter a username",
        remote: "That username is already in use",
        email: "Please use an email address as the username"
      },
      first_name: {
        required: "Please enter your first name"
      },
      last_name: {
        required: "Please enter your last name"
      },
      password: {
        required: "Please enter a password"
      }
    }
  });

});