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
			
			$Statement = $Database->prepare("SELECT id FROM featured_statuses WHERE user_id = ? AND status_id = ?");
			$Statement->execute(array($user["id"], $id[1]));
			$rowCount = $Statement->rowCount();
			
			if ($rowCount == 0)
			{
				try
				{
					$Statement = $Database->prepare("INSERT INTO featured_statuses (user_id, status_id) VALUES (?, ?)");
					$Statement->execute(array($user["id"], $id[1]));
				}
				catch (PDOException $e)
				{
					echo 'Internal Server Error';
				}
			}
			else 
			{
				try
				{
					$Statement = $Database->prepare("DELETE FROM featured_statuses WHERE user_id = ? AND status_id = ?");
					$Statement->execute(array($user["id"], $id[1]));
				}
				catch (PDOException $e)
				{
					echo 'Internal Server Error';
				}
			}
		}
	}
?>
