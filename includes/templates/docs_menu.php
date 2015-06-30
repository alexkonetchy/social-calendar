<ul>
	<li><a href="<?php echo $url; ?>/index.php" <?php if ($_SERVER["PHP_SELF"] == '/index.php') echo 'class="active"'; ?>>Home</a></li>
	<!--<li><a href="about.php" <?php if ($_SERVER["REQUEST_URI"] == '/about.php') echo 'class="active"'; ?>>About</a></li>-->
	<li><a href="contact.php" <?php if ($_SERVER["REQUEST_URI"] == '/contact.php') echo 'class="active"'; ?>>Contact</a></li>
</ul>
