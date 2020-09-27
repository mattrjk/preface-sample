<!-- fixed top nav bar for account details -->

<?php
	$greetings = array("Hi", "What's up", "Greetings", "Hey", "Howdy", "Hello", "Hola", "Top of the morning to you", "Hey there");
	$random_greeting = array_rand($greetings, 1);
?>
<div tabindex="0" class="click_menu">
	<a class="click_menu_link"></a>
    <ul class="click_menu_content">
        <li><a href="edit_account.php">Edit my account...</a></li>
        <li><a href="register.php">Add a new admin user...</a></li>
        <li><a href="actions/logout.php" class="negative_action">Logout...</a></li>
    </ul>
</div>
<div class="header">
	<div class="container">
		<div class="inner">
			<div class="photo">
				<img src="assets/img/photo_placeholder.png" height="60" width="60">
			</div>
			<div class="greeting">
				<p><?php echo $greetings[$random_greeting] . ", " . htmlentities($_SESSION['user']['first_name'], ENT_QUOTES, 'UTF-8'); ?>!</p>
			</div>
		</div>
	</div>
</div>