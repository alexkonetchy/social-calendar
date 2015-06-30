<?php
	if (!isset($Feed)) $Feed = new Feed($Database);				//Instantiates the feed class
	
	$totalNumCalendars = $UserCalendars->getNumCalendars();		//Gets the total number of calendars created on the site
?>
<div class="left-sidebar">
	<div class="left-menu">
		<ul class="left-nav-inline">
			<li><a href="<?php echo $url; ?>/manage.php" style="border-right:1px #ccc solid;" <?php if ($_SERVER["PHP_SELF"] == '/manage.php') echo 'class="active"'; ?>>
					<?php echo ($user["num_calendars"] == 0) ? 'Add New<br/>Board' : $user["num_calendars"] . '<br/>boards'; ?>
				</a>
			</li>
			<li><a href="<?php echo $url; ?>/followers.php" <?php if ($_SERVER["PHP_SELF"] == '/followers.php') echo 'class="active"'; ?>><?php echo $Profile->getNumFollowers($user["id"]); ?><br/>followers</a></li>
			<div class="clear"></div>
		</ul>
		<div style="border-bottom:1px #ccc solid;margin-bottom:6px;"></div>
		<ul>
			<li><a href="<?php echo $url; ?>/index.php">Home</a></li>
			<li><a href="<?php echo $url; ?>/index.php?type=following">Following</a></li>
			<li><a href="<?php echo $url; ?>/index.php?type=popular">Popular</a></li>
			<li><a href="<?php echo $url; ?>/index.php?type=latest">Latest</a></li>
		</ul>
		
		<?php if (isset($_GET["cid"])): ?>
		<div style="text-align:left;margin-top:10px;padding:5px 10px;border-top:1px #ccc solid;font-size:0.8em;line-height:170%;">
			<strong style="font-size:1.17em;">Top Dates</strong>
			<ul class="location-list">
				<?php
					$cid = (!empty($_GET["cid"])) ? $_GET["cid"] : 'public';	//Set the cid to identify the calendar in the link to popular dates
					
					foreach ($Feed->getPopularDates($_GET["cid"]) as $date)
					{
						echo '<li><a href="' . $url . '/calendar.php?cid=' . $cid . '&m=' . $date["month"] . '&y=' . $date["year"] . '">' . date("F jS, Y", mktime(0,0,0,$date["month"],$date["day"],$date["year"])) . '</a></li>';
					}
				?>
			</ul>
		</div>

		<?php endif; ?>
		
		<?php /*
		<div style="text-align:left;margin-top:5px;padding:5px 10px;border-top:1px #ccc solid;font-size:0.8em;line-height:170%;">
			<strong style="font-size:1.17em;">Popular Locations</strong>
			<ul class="location-list">
				<?php
					foreach ($Feed->getPopularLocations() as $list)
					{
						echo '<li><a href="' . $url . '/index.php?location=' . $list["location"] . '&update-location=Update">' . $list["location"] . '</a></li>';
					}
				?>
			</ul>
		</div>
		*/ ?>
	</div>
	<div class="footer">
		<a href="<?php echo $url . '/docs/privacy.html'; ?>">Privacy</a>
		&middot;
		<a href="<?php echo $url . '/docs/tos.html'; ?>">Terms of Service</a>
		&middot;
		<a href="<?php echo $url . '/docs/contact.php'; ?>">Contact</a>
		<!--&middot;
		<a href="<?php echo $url . '/docs/about.php'; ?>">About</a>-->
		&middot;
		<a href="<?php echo $url . '/docs/contact.php?p=feedback'; ?>">Feedback</a>
		<br/>
		<a href="<?php echo $url; ?>/signout.php">Sign Out</a>
		<br/>
		Captchur. &copy; 2012-<?php echo date('Y'); ?>
	</div>
</div>
