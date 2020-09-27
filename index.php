<!-- Main page for customers. Emailed transaction alerts contained a direct link to the proof; this form was used if we needed to guide a client through manually inputting their customer and order IDs -->
<!DOCTYPE html>
<html>
<head>
	<title>Preface</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link href="/assets/img/favicon.ico" rel="icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no">
	<link href="/assets/css/style.css" rel="stylesheet">
	<link href="/assets/css/layout.css" rel="stylesheet">
	<link media="only screen and (max-device-width: 480px)" rel="stylesheet" href="/assets/css/phone.css" type="text/css">
	<link media="only screen and (min-device-width: 541px) and (max-device-width: 800px)" rel="stylesheet" href="/assets/css/tablet.css" type="text/css">
	<!--[if IE 6]><script type="text/javascript" src="/assets/js/ie6block.js"></script><![endif]-->
	<!--[if IE 7]><script type="text/javascript" src="/assets/js/ie7block.js"></script><![endif]-->
	<!--[if !IE]><!--><script type="text/javascript" src="/assets/js/ie10css.js"></script><!--<![endif]--> 
</head>
<body>
	<span id="login">
		<div class="logo"><img src="/assets/img/preface_logo.png" class="preface-logo"></div>
		<div id="logo_subhead">
			<p class="subhead">Welcome to Preface To view your proofs manually, enter the customer and order ID's we provided you.</p>
		</div>
		<div class="signin">
			<form action="overview.php" method="get" enctype="application/x-www-form-urlencoded" class="login_form" id="loginForm">
				<ul class="login_form">
					<li>
						<label for="customer_id">Customer ID:</label><br />
						<input type="tel" id="customer_id" name="customer_id" placeholder="86" required><br />
						<label for="customer_id" class="error"></label>
					</li>
					<li>
						<label for="order_id">Order ID:</label><br />
						<input type="tel" id="order_id" name="order_id" placeholder="75309" required><br />
						<label for="order_id" class="error"></label>
					</li>
					<li><button class="submit" type="submit">View Proofs</button></li>
				</ul>
			</form>
			<?php include 'includes/footer.php' ?>
		</div>
	</span>
  <script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="/assets/js/jquery-1.10.2.min.js"><\/script>')</script>
	<script type="text/javascript" src="/assets/js/jquery.validate.js"></script>
	<script type="text/javascript" src="/assets/js/index-validate.js"></script>
</body>
</html>
