<?php
	$Search = new Search($Database);
	
	//Determine the page title
	if (!empty($_GET["q"]))
		$title = $_GET["q"];
	else if (!empty($_GET["tags"]))
		$title = $_GET["tags"];
	else if (!empty($_GET["location"]))
		$title = $_GET["location"];
	else if (!empty($_GET["date"]))
		$title = $_GET["date"];
	else if (!empty($_GET["user"]))
		$title = $_GET["user"];
		
	try 
	{
		$Search->setInclude($_GET["q"]);
		$Search->setTags($_GET["tags"]);
		$Search->setLocation($_GET["location"]);
		$Search->setDate($_GET["date"]);
		$Search->setUser($_GET["user"]);
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
	}

	if (!isset($Feed)) $Feed = new Feed($Database);

	$eventCount = 0;
	foreach($Search->query() as $status)
	{
		require $baseDir . 'includes/templates/feed_box.php';
		$eventCount++;
	} 
?>
