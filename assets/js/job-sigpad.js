// define options and size for signature pads

$(document).ready(function() {
    $('.sigPad').signaturePad({
    	drawOnly:true,
    	lineTop:100,
    	validateFields:false,
    	errorMessage:null,
    	errorMessageDraw:null,
    	errorClass:'fake_error',
    	name:null,
    	bgColour:'#ececea'
    });
    
    $('.sigPadMobile').signaturePad({
    	drawOnly:true,
    	lineTop:75,
    	validateFields:false,
    	errorMessage:null,
    	errorMessageDraw:null,
    	errorClass:'fake_error',
    	name:null,
    	bgColour:'#ececea'
    });
});