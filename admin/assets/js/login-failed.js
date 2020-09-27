//admin page login failed dialog box

$(function() {
  $( "#failed_dialog" ).dialog({
      modal: true,
      closeOnEscape: false,
    dialogClass: "no-close",
      buttons: {
          "Retry": function() {
              window.open('../index.php','_self');
          }
      }
    });
});