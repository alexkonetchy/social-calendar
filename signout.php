<?php
	/**
	 * Destroys the session and logs the user out
	 */

	session_start();
	session_destroy();
	header("Location: index.php");
	die();
?>
