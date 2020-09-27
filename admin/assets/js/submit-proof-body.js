//when dropdown is selected, the remaining client data is populated by proof-swap.js, and then the .hiddenTail is unhidden after that data

$(document).ready(function() {
    $('#client_drop').change(function(){
        var location = $(this).val();
        
        if(location !='') {
            $('.hiddenTail').show();
        }
        else if(location = '') {
            $('.hiddenTail').show();
        }
    });
    
});

//opening and closing already-defined on the page modal boxes for the add client form
function loadPopupBox() {
    $('#add-client-mini').fadeIn("fast");
}

function closePopupBox() {
    $('#add-client-mini').fadeOut("fast");
}