<?php
	session_start();
	
	if ($_GET["submit"] == 'yes')
	{
		$_SESSION["load_size"] = $_GET["size"];
	}
?>
