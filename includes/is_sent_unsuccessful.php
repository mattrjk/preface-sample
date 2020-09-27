<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Proofs - Job Overview</title>
	<link href="../assets/css/style.css" rel="stylesheet">
	<link href="../assets/css/layout.css" rel="stylesheet">
	<script type="text/javascript" src="//use.typekit.net/XXXXXXX.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>
<body>
	<div class="content" style="margin-top: 25px;">
		<div id="copy_logo" style="width: 100%; margin: 0 auto; text-align: center;">
			<a href="/"><img src="../assets/img/preface_logo_side_whitebg.png" height="60" width="301" border="0"></a>
		</div>
		<div class="job-hint">
			<p>Your copy was <span class="negative_action">not sent</span> successfully to <?php echo $is_sent['email']; ?>. Click below to go back to the overview page and make sure you've entered a valid email address.</p>
			<p><button class="send_copy" onclick="javascript:history.back(-1)">Go back</button></p>
		</div>
		<?php include 'footer.php'; ?>
	</div>
</body>
</html>
	