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
	 * Handles all of the form submissions
	 *
	 * NOTE: SEE BOTTOM OF THE PAGE TO EDIT CODE THAT HANDLES FROM SUBMISSIONS TO EDIT THE CALENDAR
	 */
	 
	//If the user is trying to follow the calendar
	if (!empty($_GET["f"]) && is_numeric($_GET["f"]) && !empty($user))		
	{
		require $baseDir . '/process_form/follow_calendar.php';				//Require the script that processes the request
		header("Location: " . $url . "/calendar.php?cid=" . $_GET["cid"]);	//Redirect to prevent re-submission
	}
	
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
			 * Display the actual calendar
			 */
		?>

	<div class="container">
		<div class="calendar-wrapper">
			<div class="top-heading" style="margin:0;">
				<?php echo $calendar["title"]; /*the calendar's title*/ ?> 
				<span style="font-size:11px;">| 
				<a href="<?php echo $url; ?>/u/<?php echo $calendar["username"]; ?>" class="no-underline"><?php echo $calendar["first_name"] . ' ' . $calendar["last_name"]; ?></a>
				<br/>
				<span style="font-size:11px;font-style:italic;color:#777;"><?php echo $calendar["description"]; /*The calendars description*/ ?></span>
			</div>	
			<div class="calendar-sub-menu">
				
				<?php if (!empty($user)): /*Only display if a user is logged in*/ ?>
				<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>&f=<?php echo $_GET["cid"]; ?>"><?php echo ($Profile->followingCalendar($_GET["cid"], $user["id"]) == true) ? 'Unfollow' : 'Follow'; ?></a>
				
					<?php if ($user["id"] == $calendar["user_id"]): ?>
						<a href="#edit-calendar" rel="popup">Edit</a>
					<?php endif; ?>	
				<?php endif; /*End only display if a user is logged in*/ ?>
				
				<a href="<?php echo $url; ?>/timeline.php?cid=<?php echo $calendar["id"]; ?>">Timeline</a>
				<div class="clear"></div>
			</div>
			<div class="date">
				<h2>	
					<!--Decrement the month-->	
					<?php $previousDate = Calendar::getPrevious($_GET["m"], $_GET["y"]); ?>	
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?cid=<?php echo $_GET["cid"]; ?>&m=<?php echo $previousDate["month"]; ?>&y=<?php echo $previousDate["year"]; ?>" alt="Previous Month" ><img src="<?php echo $url; ?>/images/left-arrow.png" alt="Previous Month" /></a>
							
						<?php echo Calendar::getTitle($_GET["m"],$_GET["y"]); /*Display the month and the year*/ ?> 
							
					<!--Increment the month-->
					<?php $nextDate = Calendar::getNext($_GET["m"], $_GET["y"]); ?>	
					<a href="<?php echo $_SERVER["PHP_SELF"]; ?>?cid=<?php echo $_GET["cid"]; ?>&m=<?php echo $nextDate["month"]; ?>&y=<?php echo $nextDate["year"]; ?>" alt="Next Month" ><img src="<?php echo $url; ?>/images/right-arrow.png" alt="Next Month" /></a>
				</h2>
			</div>
			<?php require $baseDir . 'includes/templates/calendar.php'; /*Display the calendar*/ ?>	
			<div class="clear"></div>
			<div style="text-align:right;margin-top:10px;">
				<div style="display:inline;text-align:left;padding:1px 8px;">
					<a href="#share-user-calendar" rel="popup"><img src="images/share-button.png"></a>
				</div>
			</div>
		</div>
	</div>
		
		<?php
		###########################################################
		/**
		 * End displaying the calendar
		 */
		###########################################################
		?>
		
		<div class="container">
			<div class="top-heading">Featured Posts</div>	
				<?php
					#####################################################
					/**
					 * Display the latest posts
					 */
					$dataTypeFeed = true;
					$eventCount = 0;	//Number of events
					foreach ($Feed->statuses_CalendarFeatured($_GET["cid"]) as $status)
					{
						require $baseDir . 'includes/templates/feed_box.php';	//Includes the template for the status box
						$eventCount++; 	//Count the number of statuses
					}	//end the cycle through the statuses
					unset($dataTypeFeed);
					
					if ($eventCount == 0)
						echo '<div style="color:#aaa;font-style:italic;">This calendar doesn\'t have any featured posts.</div>';
				?>
			</div>	
		</div>
		<div class="clear"></div>
	</div> <!--.content-wrapper-->
	
		<!--Share popup-->
		<div class="popup" id="share-user-calendar" style="width:500px;">
			<div class="popup-heading">
				Share the Calendar
				<div style="float:right;margin-right:4px;">
					<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="popup-contain" style="padding:10px;">
				<div class="fb-like" data-send="false" data-width="450" data-show-faces="true"></div>
				<br/><br/>
				<a href="https://twitter.com/share" class="twitter-share-button" data-text="Awesome calendar <?php echo $url . $_SERVER["REQUEST_URI"]; ?>" data-size="large" data-hashtags="datehitter">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<br/><br/>
				<div class="g-plusone"></div>
				
				<div style="text-align:center;margin:10px 0;">
					<strong>Embed the Calendar</strong>
					<p>Copy the code below to embed the calendar on your website</p>
					<textarea style="width:90%;max-width:90%;height:60px;border:1px #ccc solid;padding:5px;"><iframe src="<?php echo $url; ?>/api/calendar.php?cid=<?php echo $calendar["id"]; ?>" style="width:260px;height:250px;border:0;overflow:hidden;border-radius:5px;" seamless="seamless" scrolling="no"></iframe></textarea>
				</div>
			</div>
		</div>
		
		<?php if ($user["id"] == $calendar["user_id"]):	/*If the user is viewing their own calendar*/ ?>
		
			<div id="edit_calendar_preferences" class="popup" style="width:450px;">
				<div class="popup-heading">
					Edit Calendar
					<div style="float:right;margin-right:4px;">
						<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="popup-contain" style="text-align:center;">
				 	<form action="" method="post" style="padding:15px;">
				 		<input type="text" class="post-input" name="calendar_title" style="width:375px;" value="<?php echo $calendar["title"]; ?>" />
				 		<br/>
				 		<textarea name="calendar_description" class="post-input" style="width:375px;max-width:375px;height:70px;"><?php echo $calendar["description"]; ?></textarea>
				 		<br/>
				 		<div style="text-align:left;background:#f3f3f3;border:1px #bbb solid;margin:5px 15px;padding:6px;display:none;" id="c-pref-box">
					 		<strong style="margin-bottom:5px;display:block;">Permission Settings</strong>
					 		<input type="radio" name="permissions" value="0" <?php if ($calendar["permission"] == 0) echo 'checked'; ?>><label>Anyone can update</label>
					 		<br/>
					 		<input type="radio" name="permissions" value="1" <?php if ($calendar["permission"] == 1) echo 'checked'; ?>><label>Only I can update</label>
					 	</div>
				 		<br/>
				 		<input type="submit" value="Edit" name="edit_calendar_submit" class="a-button" />
				 		<a href="javascript:toggle('c-pref-box');" style="font-size:11px;">Change Settings</a>
				 	</form>
				</div>
			</div>
			
		<?php endif; /*End if the user is viewing their own calendar*/ ?>

		<?php
		
		##################################################################################################################################################
		/**																																			######
		 * This block of code handles all of the form submissions 																					######
		 * that deal with the calendar itself																										######
		 */																																			######
																																					######
		//If the user is editing their calendar 																									######
		if (isset($_POST["edit_calendar"]) && $calendar["user_id"] == $user["id"])																	######
		{																																			######
			try																																		######
			{																																		######
				$UpdateCalendar = new UpdateCalendar($calendar["id"], $Database);	//Instantiate the update class									######
				$UpdateCalendar->setTitle($_POST["calendar_title"]);				//The calendar's title											######
				$UpdateCalendar->setDescription($_POST["calendar_description"]);	//The calendar's description									######
				$UpdateCalendar->editCalendar();									//Execute the query												######
			}																																		###### 
			catch (Exception $e)																													######
			{																																		######
				echo $e->getMessage();																												######
			}																																		######
																																					######
			header ("Location: " . $_SERVER["REQUEST_URI"]);						//Refresh the page to prevent form resubmission					######
		}																																			######
																																					######
		//If the user is deleting their calendar																									######
		if ($_GET["delete"] == 'yes' && $calendar["user_id"] == $user["id"])																		######
		{																																			######
			try																																		######
			{																																		######
				$UpdateCalendar = new UpdateCalendar($calendar["id"], $Database);	//Instantiate the update class									######
				$UpdateCalendar->deleteCalendar($user["id"]);						//Delete the calendar											######
			}																																		######
			catch (Exception $e)																													######
			{																																		######
				echo $e->getMessage();																												######
			}																																		######
																																					######
			header("Location: " . $url.'/index.php');								//Redirect to prevent form resubmission							######
		}																																			######
																																					######
		//If the user is making this calendar their primary calendar																				######
		if ($_GET["primary"] == 'yes' && $calendar["user_id"] == $user["id"])																		######
		{																																			######
			try																																		######
			{																																		######
				$UpdateCalendar = new UpdateCalendar($calendar["id"], $Database);	//Instantiate the update class									######
				$UpdateCalendar->makePrimary($user["id"]);							//Set the calendar as the primary one							######
			}																																		######
			catch (Exception $e)																													######
			{																																		######
				echo $e->getMessage();																												######
			}																																		######
																																					######
			header("Location: " . $url.'/calendar.php?cid=' . $calendar["id"]);		//Redirect to prevent form resubmission							######
		}																																			######
																																					######
		//If the user is adding their facebook data to a calendar																					######
		if ($_GET["action"] == 'addfbdata' && $user["id"] == $calendar["user_id"])																	######
		{																																			######	
			require $baseDir . 'includes/add_facebook_data.php';																					######	
			header("Location: " . $url.'/calendar.php?cid='.$calendar["id"]);		//Redirect to prevent form resubmission							######
		} 																																			######
																																					######
		##################################################################################################################################################
		##################################################################################################################################################
		?>		
<!--Extra popups-->

<div class="popup" id="edit-calendar" style="width:500px;">
	<div class="popup-heading">
		Edit <?php echo $calendar["title"]; ?>
		<div style="float:right;margin-right:4px;">
			<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="popup-contain" style="padding:7px;">
		<form action="#" style="margin-top:10px;margin-bottom:20px;" method="post">
			<input type="text" name="calendar_title" style="width:80%;" class="post-input" value="<?php echo $calendar["title"]; ?>" />
			<br/>
			<textarea name="calendar_description" style="width:80%;" class="post-input"><?php echo $calendar["description"]; ?></textarea>
			<br/>
			<input type="submit" name="edit_calendar" value="Edit Calendar" class="a-button">
		</form>
		&nbsp;<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>&delete=yes" onClick="return confirm('Are you sure you want to delete this calendar?');">Delete</a>
		&middot;
		<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $calendar["id"]; ?>&primary=yes">Make Primary Calendar</a>
		<?php
			if ($_SESSION["login_type"] == 'facebook') 
				echo '&middot; <a href="'.$url.'/calendar.php?cid='.$calendar["id"].'&action=addfbdata">Add Your Facebook Data</a>';
		?>
	</div>
</div>

<!--Javascript-->
<script type="text/javascript">document.getElementById('calendar-id-<?php echo $_GET["cid"]; ?>').selected = true;</script>
<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
