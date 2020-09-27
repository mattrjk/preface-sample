// check for IE6 user agent and redirect

var IE6 = (navigator.userAgent.indexOf("MSIE 6")>=0) ? true : false;

if(IE6) {
	window.location.replace("https://proofs.domain.com/ie6unsupported.php");
}