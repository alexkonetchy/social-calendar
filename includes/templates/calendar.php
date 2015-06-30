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
		$status = Feed::sortDay($feed, $day);								//Sort the feed for the day
	?>		
		<li>
			<?php if (sizeof($status) > 1): /*If there is content in the array for the day*/ ?>
			<a class="calendar-link" rel="popup" href="#<?php echo $calTitle; ?>popup<?php echo $day; ?>"><?php echo $day; ?><br/>
				<span id="display_day_<?php echo $day; ?>" style="color:#777;font-weight:normal;font-size:0.85em;">&bull;</span>
			</a>
			<?php else: /*If there isn't anything posted for the day*/ ?>
			<span class="calendar-link"><?php echo $day; ?></span>
			<?php endif; /*End the format for the calendar*/ ?>
		</li>
		
		<?php if (sizeof($status) > 1): ?>
			<div id="<?php echo $calTitle; ?>popup<?php echo $day; ?>" class="status-popup"><?php require $baseDir . 'includes/templates/calendar_popup_feed_box.php';	/*Include the template for the status box*/ ?></div>
		<?php endif; ?>
				
		<?php endfor; /*Ends the calendar display*/ ?> 
	</ul>
	
