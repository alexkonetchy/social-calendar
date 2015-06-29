<?php
 	require '../config.php';
 	require $baseDir . 'includes/standard_page_load.php';
 	
 	//If a user is logged in
	if ($_SESSION["in"] == true)
	{
		//If the form was submitted
		if ($_GET["submit"] == 'yes')
		{
			$id = explode('_', $_GET["id"]);
			try
			{
				$Statement = $Database->prepare("DELETE FROM statuses WHERE user_id = ? AND id = ?");
				$Statement->execute(array($user["id"], $id[1]));
			}
			catch (PDOException $e)
			{
				echo 'Internal Server Error';
			}
			
			echo '1';
		}
	}
?>
