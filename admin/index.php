<!-- main login page for admin interface. Redirect point if any pages are accessed from a non-logged in session -->

<!DOCTYPE html>
<html>
<head>
	<title>Preface</title>
	<link href="assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="assets/css/layout.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
	<span id="login">
		<div class="logo"><img src="assets/img/preface_logo.png" height="200" width="498"></div>
		<div id="logo_subhead">
			<p class="subhead">Administrator Login</p>
		</div>
		<form action="actions/login.php" method="post" class="default_form" id="login_form">
			<ul class="default_form">
				<li><label for="username">Username:</label></li>
			    <li><input type="text" name="username" id="username" value="" /><br /><label for="username" class="error"></li>
			    <li><label for="password">Password:</label></li>
			    <li><input type="password" name="password" id="password"/><br /><label for="password" class="error"></li>
				<li><button class="submit" type="submit" id="submit">Login</button></li>
			</ul>
		</form> 
  </span>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="assets/js/jquery.min.js"><\/script>')</script>
	<script type="text/javascript" src="assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="assets/js/index-validate.js"></script>
	<script type="text/javascript" src="assets/js/mousetrap.min.js"></script>
  <script type="text/javascript" src="assets/js/keyboard-shortcuts.js"></script>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</body>
</html>