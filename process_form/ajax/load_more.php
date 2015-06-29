<?php
require '../../config.php';

$Feed = new Feed($Database);

$eventCount = 0;	//Number of events
foreach ($Feed->statuses_CalendarLatest($_GET["cid"], 5, 10) as $status)
{
	require $baseDir . 'includes/templates/feed_box.php';	//Includes the template for the status box
	$eventCount++; 	//Count the number of statuses
}	//end the cycle through the statuses

?>
