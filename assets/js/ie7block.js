// check for IE7 user agent and redirect

var IE7 = (navigator.userAgent.indexOf("MSIE 7")>=0) ? true : false;

if(IE7) {
	window.location.replace("https://proofs.domain.com/ie7unsupported.php");
}