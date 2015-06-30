<?php

/**
 * If the user is logged in then this page doesn't matter, however, if
 * a user isn't logged in this page will redirect them to the appropriate
 * page. 
 *
 * The file should be included after the user has been validated
 */

	if (empty($user))
	{
		header('Location: ' . $url . '/login.php');
		die();
	}
		
?>
