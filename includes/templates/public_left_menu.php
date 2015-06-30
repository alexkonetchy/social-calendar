<?php
	if (!isset($Feed)) $Feed = new Feed($Database);
?>
<div class="left-sidebar">
	<div class="left-menu">
		<ul>
			<li><a href="<?php echo $url; ?>/index.php" <?php if ($_SERVER["PHP_SELF"] == '/index.php') echo 'class="active"'; ?>>Home</a></li>
			<li><a href="<?php echo $url; ?>/login.php">Sign Up</a></li>
		</ul>
		
		<div style="text-align:left;margin-top:10px;padding:5px 10px;border-top:1px #ccc solid;font-size:0.8em;line-height:170%;">
			<strong style="font-size:1.17em;">Popular Dates</strong>
			<ul class="location-list">
				<?php
					$datesArray = $Feed->getPopularDates();
					
					foreach ($datesArray as $date)
					{
						echo '<li><a href="' . $url . '/calendar.php?cid=public&m=' . $date["month"] . '&y=' . $date["year"] . '">' . date("F jS, Y", mktime(0,0,0,$date["month"],$date["day"],$date["year"])) . '</a></li>';
					}
				?>
			</ul>
		</div>
		<div style="text-align:left;margin-top:5px;padding:5px 10px;border-top:1px #ccc solid;font-size:0.8em;line-height:170%;">
			<strong style="font-size:1.17em;">Popular Locations</strong>
			<ul class="location-list">
				<?php
					$locationArray = $Feed->getPopularLocations();
					
					foreach ($locationArray as $list)
					{
						echo '<li><a href="' . $url . '/index.php?location=' . $list["location"] . '&update-location=Update">' . $list["location"] . '</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
	<?php require $baseDir . 'includes/templates/footer_box.php'; /*Displays the footer box*/ ?>
</div>
