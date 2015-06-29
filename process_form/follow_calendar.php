<?php
	$Statement = $Database->prepare("SELECT id FROM following_calendar WHERE calendar_id = ? AND user_id = ?");
	$Statement->execute(array($_GET["f"], $user["id"]));
	$rowCount = $Statement->rowCount();
	
	if ($rowCount == 0)
	{
		$Statement = $Database->prepare("UPDATE calendars SET num_followers = num_followers + 1 WHERE id = ?");
		$Statement->execute(array($_GET["f"]));
		
		$Statement = $Database->prepare("INSERT INTO following_calendar (calendar_id, user_id) VALUES (?, ?)");
		$Statement->execute(array($_GET["f"], $user["id"]));
	}
	else
	{
		$Statement = $Database->prepare("UPDATE calendars SET num_followers = num_followers - 1 WHERE id = ?");
		$Statement->execute(array($_GET["f"]));
		
		$Statement = $Database->prepare("DELETE FROM following_calendar WHERE calendar_id = ? AND user_id = ?");
		$Statement->execute(array($_GET["f"], $user["id"]));
	}
?>
