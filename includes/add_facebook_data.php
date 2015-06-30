<?php
/**
 * This page can only work if it is included in another page while a user is logged in.
 * The page's primary purpose is to add data from the user's facebook account to a 
 * calendar.
 */
 
 #############################################################################################
 
	$Image = new Image($Database);
			
	//Prepare the facebook sql for getting photos
	$fql = "SELECT src_big, created, caption FROM photo WHERE owner = me()";
	$param = array('method' => 'fql.query', 'query' => $fql, 'callback' => '');
	$result = $Facebook->api($param);

	//Get the results
	foreach ($result as $row)
	{
		//Check to see if a video is already posted
		$Statement = $Database->prepare("SELECT id FROM statuses WHERE month = ? AND day = ? AND year = ? AND calendar_id = ?");
		$Statement->execute(array(gmdate("m", $row["created"]), gmdate("d", $row["created"]), gmdate("Y", $row["created"]), $calendar["id"]));
		$rowCount = $Statement->rowCount();
		
		//If it is then skip
		if ($rowCount != 0) continue;
		
		
		//Update the database with the photos
		try
		{
			$Image->setUrlPath($row["src_big"]);
			$Image->setContent(substr($row["caption"], 0, 900) . ' Photo taken ' . gmdate("m-d-Y", $row["created"]));
			$Image->setCategory('image');
			$Image->setTags($row["caption"]);
			$Image->setMonth(gmdate("m", $row["created"]));
			$Image->setDay(gmdate("d", $row["created"]));
			$Image->setYear(gmdate("Y", $row["created"]));
			$Image->setLocation('');
			$Image->setCalendarId($calendar["id"]);
			$Image->query($user["id"]);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
	}

?>
