<?php
	#####################################################
	/**
	 * The page settings
	 */
	require 'config.php';										//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';		//Loads most common features (i.e. Facebook api)
	
	######################################################
	/**
	 * Handles a user's first login
	 */
	if (isset($_SESSION["first_login"]))
	{
		
	}
	
	######################################################
	/**
	 * Starts the html of the page
	 */
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>

	<div class="wrapper">
		<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>
		
		<div class="content-wrapper" style="text-align:left;">
			
			<div style="text-align:center;" id="feed-content">
				<?php
						//Initialize the class for user calendars if it hasn't been already
						if (!isset($UserCalendars)) $UserCalendars = new UserCalendars(date('m'), date('Y'), $Database);
						
						/**
						 * Switch the type of calendar feed based on the page parameter that is 
						 * supplied. If none then display the default
						 */
						switch ($_GET["type"])
						{
							case 'popular':		//Get the most popular calendars
								foreach ($UserCalendars->getTop20() as $calendar)	//Cycle through the array returned by the query
									require $baseDir . 'includes/templates/calendar_preview_box.php';
								break;
							case 'public':
								require $baseDir . 'includes/templates/public_calendar.php';
								break;
							case 'latest':		//Get the latest calendars that were created
								foreach ($UserCalendars->getLatest() as $calendar)	//Cycle through the array returned by the query
									require $baseDir . 'includes/templates/calendar_preview_box.php';
								break;
							case 'following':	//Get the calendars that the user is following
							default:			//THE DEFAULT DISPLAY
								$count = 0;		//Initial count is zero
								foreach ($UserCalendars->getFollowing($user["id"]) as $calendar)
								{
									require $baseDir . 'includes/templates/calendar_preview_box.php';
									$count++;	//Add one
								}
									
								//If the user isn't following anything display a message
								if ($count == 0) 
								{
									echo '<div style="text-align:left;margin-left:5px;margin-top:20px;margin-bottom:20px;"><div style="background:#062327;color:#fff;text-align:left;padding:6px;display:inline">You\'re not following any calendars yet so we\'re listing the most popular ones for you here.</div></div>';
									foreach ($UserCalendars->getTop20() as $calendar)	//Cycle through the array returned by the query
										require $baseDir . 'includes/templates/calendar_preview_box.php';	
								}
						}
				?>
				<div class="clear"></div>
			</div>
		</div>
		
		<script type="text/javascript" src="<?php echo $url; ?>/javascript/jquery.masonry.min.js"></script>
		<script type="text/javascript">var $container = $('#feed-content'); $container.imagesLoaded( function(){ $container.masonry({ itemSelector : '.square-feed-box' }); });</script>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page	
?>
