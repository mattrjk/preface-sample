<!-- fixed sidebar nav -->
<!-- TODO: don't use PHP for active page. JS instead? -->

<div class="sidebar">
	<div class="sidebar_logo">
		<a href="private.php"><img src="../assets/img/preface_logo.png" height="80" width="200" border="0"></a>
	</div>
	<hr class="sidebar">
	<div class="sidebar_nav">
		<ul class="sidebar_nav">
			<li <?php if(basename($_SERVER['PHP_SELF']) == 'submit_proof.php'){echo 'class="active_nav"';} ?>><a href="submit_proof.php"><img src="assets/img/sidebar_new_proof.png" width="30" height="30" border="0">Upload a new proof</a></li>
			<li <?php if(basename($_SERVER['PHP_SELF']) == 'open_proofs.php'){echo 'class="active_nav"';} ?>><a href="open_proofs.php"><img src="assets/img/sidebar_open_proofs.png" width="30" height="30" border="0">View open proofs</a></li>
			<li <?php if(basename($_SERVER['PHP_SELF']) == 'closed_proofs.php'){echo 'class="active_nav"';} ?>><a href="closed_proofs.php"><img src="assets/img/sidebar_closed_proofs.png" width="30" height="30" border="0">View closed proofs</a></li>
			<li><a href="private.php" class="positive_action"><img src="assets/img/sidebar_home.png" width="30" height="30" border="0">Go home</a></li>
		</ul>
	</div>
</div>