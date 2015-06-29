<?php
	#####################################################
	/**
	 * The settings for the page
	 */
	$publicPage = true;
	require 'config.php';										//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';		//Loads most common features (i.e. Facebook api)

	#####################################################
	/**
	 * Starts the actual html of the page with the header
	 */
	if (!empty($user))	//If the user is logged in
		require $baseDir . 'includes/templates/page_header.php';
	else				//If the user isn't logged in
	 	require $baseDir . 'includes/templates/public_page_header.php';	
	 	
?>

	<div class="wrapper">
		<?php 
			#####################################################
			/**
			 * Display the left menu
			 */
			if (empty($user)) 	//If a user is not logged in
				require $baseDir . 'includes/templates/public_left_menu.php';
			else				//If a user is logged in
				require $baseDir . 'includes/templates/left_menu.php'; 
		?>
				
		<div class="content-wrapper">
		<?php 
			#####################################################
			/**
			 * Get the data for the calendar
			 */
			  
			//Instantiate the Calendar Class depending on page parameters
			$UserCalendars = new UserCalendars($_GET["m"], $_GET["y"], $Database);		
				
			//Variables for the calender
			$empty		= $UserCalendars->getEmpty();	//The number of empty boxes at start of the month
			$month 		= $UserCalendars->getMonth();	//The numeric representation of the month
			$year 		= $UserCalendars->getYear();	//The numeric representation of the year
			$total		= $UserCalendars->getTotal();	//The total number of days for the specified month
			$calendar	= $UserCalendars->getCalendar($_GET["cid"]);
			
			if (!isset($Feed)) $Feed = new Feed($Database);						//Instantiate the feed class
			$feed = $Feed->getUserCalendarFeed($month, $year, $_GET["cid"]);	//Get the public feed for the calendar
			$title = $calendar["title"];										//The page title
			
			#####################################################
			/**
			 * Display the actual calendar
			 */
		?>

		<div class="top-heading" style="margin:0;text-align:center;">
			Timeline -
			<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>" style="color:#000;text-decoration:none;"><?php echo $calendar["title"]; /*the calendar's title*/ ?></a>
			<div style="margin-top:6px;font-size:11px;font-style:italic;color:#777;"><?php echo $calendar["description"]; /*The calendars description*/ ?></div>
			<div style="font-size:11px;margin-top:6px;"><a href="<?php echo $url; ?>/u/<?php echo $calendar["username"]; ?>" class="no-underline"><?php echo $calendar["first_name"] . ' ' . $calendar["last_name"]; ?></a></div>
		</div>	
		
		<?php
		
		$Statement = $Database->prepare("SELECT DISTINCT year FROM statuses WHERE calendar_id = ? ORDER BY year DESC");
		$Statement->execute(array($calendar["id"]));
		$date = $Statement->fetchAll();
		
		?>
		<div class="timeline">
			<?php 
				foreach ($date as $year): 
				
				$Statement = $Database->prepare("SELECT DISTINCT month FROM statuses WHERE calendar_id = ? AND year = ? ORDER BY month DESC");
				$Statement->execute(array($calendar["id"], $year["year"]));
				$row = $Statement->fetchAll();
			?>
			
			<div class="timeline-year"><a href="javascript:toggle('display-<?php echo $year["year"]; ?>');"><?php echo $year["year"]; ?></a></div>
			<div id="display-<?php echo $year["year"]; ?>" <?php if ($year["year"] != date('Y')) echo 'style="display:none;"'; ?>>
				<?php foreach ($row as $month): ?>
				
				<div class="timeline-month" id="display-month-<?php echo $month["month"]; ?>"><a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>&m=<?php echo $month["month"]; ?>&y=<?php echo $year["year"]; ?>"><?php echo date('F', mktime(0,0,0,$month["month"],1,1)); ?></a>
				<?php 
					$Statement = $Database->prepare("SELECT content FROM statuses WHERE month = ? AND year = ? AND calendar_id = ? ORDER BY RAND() LIMIT 1");
					$Statement->execute(array($month["month"], $year["year"], $calendar["id"]));
					$row = $Statement->fetch(PDO::FETCH_ASSOC);
				?>
				<div style="font-style:italic;color:#bbb;font-size:11px;"><?php echo $row["content"]; ?></div>
				</div>
				
				<?php endforeach; ?>
			</div>
			
			<?php endforeach; ?>
		</div>
	</div>
<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
