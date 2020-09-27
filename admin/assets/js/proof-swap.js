//this script is used when a client name is selected from the dropdown menu on the new proof submission page. The rest of the data is populated by the get_email.php action

function changeEmail(str) {
	if (str=="") {
		document.getElementById("txtHint").innerHTML="";
		  return;
	}

	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	}

	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","actions/get_email.php?q="+str,true);
	xmlhttp.send();
}