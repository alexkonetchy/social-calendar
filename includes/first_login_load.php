<?php
	//Query the events from facebook
	$fql = "SELECT eid, location, description, start_time, creator FROM event WHERE eid IN (SELECT eid FROM event_member WHERE uid = me())";
	$param = array('method' => 'fql.query', 'query' => $fql, 'callback' => '');
	$result = $Facebook->api($param);

	foreach ($result as $row)
	{
		$description = substr($row["description"], 0, 1001);
		$time = explode('T', $row["start_time"]);
		$date = explode('-', $time[0]);
		$arrayTime = explode(':', $time[1]);
		
		$time = $arrayTime[0] . ':' . $arrayTime[1] . ':00';
		
		//Check to see if the event already exists
		$Statement = $Database->prepare("SELECT id FROM statuses WHERE fb_event_id = ?");
		$Statement->execute(array($row["eid"]));
		$count = $Statement->rowCount();
		
		if ($count == 0)
		{ 
			$creator = $Facebook->api($row["creator"]);
			//If not add it to the database
			$Statement = $Database->prepare("INSERT INTO statuses (user_id, fb_event_id, fb_event_username, fb_event_creator, month, day, year, hour, minute, content, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$Statement->execute(array($user["id"], $row["eid"], $creator["username"], $creator["name"], $date[1], $date[2], $date[0], '12', '00', $description, $row["location"]));
			
			$Statement = $Database->prepare("INSERT INTO rsvp (event, user, status) VALUES (?, ?, ?)");
			$Statement->execute(array($row["eid"], $user["id"], 'going'));
		}
	}
?>
