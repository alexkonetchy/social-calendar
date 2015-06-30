<?php if(!isset($Feed)) $Feed = new Feed($Database); ?>
<!--START TIMELINE-->
		<div class="container">
			<div class="top-heading">Coming Up</div>	
			<div style="text-align:left;">
				<?php 
					$eventCount = 0;	//Number of events
					foreach ($Feed->statuses_PublicUpcoming($user["id"]) as $status) //List the events nearby
					{
						require $baseDir . 'includes/templates/feed_box.php';	//Includes the template for the status box
						$eventCount++; 	//Count the number of statuses
					}	//end the cycle through the statuses
				
					if ($eventCount == 0 && $_SERVER["PHP_SELF"] == '/index.php')
						echo '<div style="text-align:center;color:#666;margin-top:10px;padding:0 15px;">There is nothing coming up. You can do two things to fix this.<br/><br/>Follow more people <strong>-or-</strong> <a href="https://www.facebook.com/pages/Datehitter/627486493933317" target="_blank">Invite your friends</a></div>'; 
				?>
			</div>
		</div>	

		<?php 
			/**
			 * The following code is used to create the calendar. The calendar class
			 * which creates it must be instantiated before the html template is included. 
			 * This helps ensure that we are able to control the calendar and the way it 
			 * operates independently based on each page. We instantiate a different
			 * Calendar class depending on a supply of page parameter or not
			 */
			 
			//Instantiate the Calendar Class
			if (!empty($_GET["m"]) && !empty($_GET["y"]))
				$Calendar = new Calendar($_GET["m"], $_GET["y"]);	
			else
				$Calendar = new Calendar(date('m'), date('Y'));
				
			//Variables for the calender
			$empty		= $Calendar->getEmpty();	//The number of empty boxes at start of the month
			$month 		= $Calendar->getMonth();	//The numeric representation of the month
			$year 		= $Calendar->getYear();		//The numeric representation of the year
			$total		= $Calendar->getTotal();	//The total number of days for the specified month
			
			$feed = $Feed->getPublicFeed($month, $year);
		?>
		
	<div id="calendar-scroller" style="position:absolute;right:0;">
		<div class="calendar-wrapper">
			<div class="top-heading">Public Calendar</div>	
			<div class="date">
				<h2>	
					<!--Decrement the month-->	
					<?php $previousDate = Calendar::getPrevious($_GET["m"], $_GET["y"]); ?>	
					<a href="<?php echo $url; ?>/calendar.php?cid=public&m=<?php echo $previousDate["month"]; ?>&y=<?php echo $previousDate["year"]; ?>" alt="Previous Month" ><img src="<?php echo $url; ?>/images/left-arrow.png" alt="Previous Month" /></a>
							
						<?php echo Calendar::getTitle($_GET["m"],$_GET["y"]); /*Display the month and the year*/ ?> 
							
					<!--Increment the month-->
					<?php $nextDate = Calendar::getNext($_GET["m"], $_GET["y"]); ?>	
					<a href="<?php echo $url; ?>/calendar.php?cid=public&m=<?php echo $nextDate["month"]; ?>&y=<?php echo $nextDate["year"]; ?>" alt="Next Month" ><img src="<?php echo $url; ?>/images/right-arrow.png" alt="Next Month" /></a>
				</h2>
			</div>
				<div class="day">Sun</div>
				<div class="day">Mon</div>
				<div class="day">Tue</div>
				<div class="day">Wed</div>
				<div class="day">Thu</div>
				<div class="day">Fri</div>
				<div class="day">Sat</div>
				<div class="clear"></div>
					
				<!--Start Calendar Days-->
				<ul>
				<?php 
					for ($count = 0; $count < $empty; $count++) echo '<li>&nbsp;</li>';	//Displays the appropriate amount of empty days	
					for ($day = 1; $day <= $total; $day++): 							//Cycle through the days
					$newsFeed = Feed::sortFeed($feed, $day, 0);								//Sort the feed for the day
				?>		
					<li>
						<?php if (sizeof($newsFeed) > 1): /*If there is content in the array for the day*/ ?>
						<a class="calendar-link" rel="popup" href="#<?php echo $calTitle; ?>popup<?php echo $day; ?>"><?php echo $day; ?><br/>
							<span id="display_day_<?php echo $day; ?>" style="color:#777;font-weight:normal;font-size:0.85em;"></span>
						</a>
						<?php else: /*If there isn't anything posted for the day*/ ?>
						<span class="calendar-link"><?php echo $day; ?></span>
						<?php endif; /*End the format for the calendar*/ ?>
					</li>
					
					<?php if (sizeof($newsFeed) > 1): ?>
					
						<!--The popup box-->
						<div id="<?php echo $calTitle; ?>popup<?php echo $day; ?>" class="popup">
							<div class="popup-heading">
								<?php echo date('l F jS, Y', mktime(0,0,0,$month,$day,$year)); /*Displays the textual day of the week, textual month, day, suffix and year*/ ?>
								
								<div style="float:right;margin-right:4px;">
									<a href="#new-post-box" rel="popup" onClick="updateSelected('select-day-<?php echo $day; ?>', 'popup<?php echo $day; ?>');"><img src="<?php echo $url; ?>/images/add-post.png"></a>
									<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
								</div>
								<div class="clear"></div>
							</div>	
								
							<div class="popup-feed">
						
								<?php 
									$eventCount = 0;							//The inital count of posts
									foreach ($newsFeed as $status) 				//Cycle through the array of statuses
									{
										require $baseDir . 'includes/templates/feed_box.php';	//Include the template for the status box
										$eventCount++;											//Count the number of posts
									} 	
								?>	
								<div class="feed-end-message"><?php echo ($eventCount == 0) ? 'This is awkward. There\'s no posts' : 'That\'s all the posts for this day'; ?></div>
								<?php if ($eventCount > 0) echo '<script type="text/javascript">document.getElementById(\'display_day_'.$day.'\').innerHTML = \''.$eventCount.' posts\';</script>'; ?>
							</div>
						</div>
					<?php endif; ?>
							
					<?php endfor; /*Ends the calendar display*/ ?> 
				</ul>
		
			<div class="clear"></div>
		</div>
