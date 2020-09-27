// this script is used to replace the text at #txtHint on the closed proofs page once the user selects a client from the dropdown

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

	xmlhttp.open("GET","actions/get_closed_proofs.php?q="+str,true);
	xmlhttp.send();
}