<?php
	#####################################################
	/**
	 * The page settings
	 */
	require 'config.php';										//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';		//Loads most common features (i.e. Facebook api)
	$title = 'Manage Calendars';	//The page title
	
	#####################################################
	/**
	 * Handles all form submissions
	 */
	 
	if (isset($_POST["add_calendar_submit"]))									//If a user is creating a new calendar
	{
		$CreateCalendar = new CreateCalendar($Database);						//Instantiate the class
		
		try
		{
			$CreateCalendar->setTitle($_POST["calendar_title"]);				//The calendar title
			$CreateCalendar->setDescription($_POST["calendar_description"]);	//The calendar description
			$CreateCalendar->setUserId($user["id"]);							//The user creating the calendar
			$CreateCalendar->create();											//Query the database to create the calendar
		}
		catch (Exception $e)
		{
			echo $e->getMessage();	//Catch any errors
		}
	}
	
	#####################################################
	/**
	 * The html for the page. Includes the header
	 */
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>

<div class="wrapper">
	<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>
		
	<div class="content-wrapper">
		<div class="top-heading">Your Calendars (<?php echo $user["num_calendars"]; ?>)</div>
		
		<div class="calendar-preview-box">
			<a href="#add-calendar-box" rel="popup" class="add-calendar-link"><img src="images/add-new-calendar.png" alt="Add New Calendar" title="Add New Calendar"></a>
		</div>
		
		<div id="add-calendar-box" class="popup" style="width:450px;">
			<div class="popup-heading">
				Add New Calendar
				<div style="float:right;margin-right:4px;">
					<img src="<?php echo $url; ?>/images/close-window.png" class="popup-close" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="popup-contain" style="text-align:center;">
			 	<form action="" method="post" style="padding:15px;">
			 		<input type="text" class="post-input" name="calendar_title" style="width:375px;" placeholder="Calendar Title" required />
			 		<br/>
			 		<textarea name="calendar_description" class="post-input" style="width:375px;max-width:375px;height:70px;" placeholder="Calendar Description (100 characters)" required></textarea>
			 		<br/>
			 		<input type="submit" value="Add Calendar" name="add_calendar_submit" class="a-button" />
			 	</form>
			</div>
		</div>
		
		<?php 
			$UserCalendars = new UserCalendars(date('m'), date('Y'), $Database);	
			
			$calendarCount = 0;
			foreach ($UserCalendars->getUserCalendars($user["id"]) as $calendar) //Display the calendars
			{
				require $baseDir . 'includes/templates/calendar_preview_box.php';
				$calendarCount++;
			}
			
			if ($calendarCount == 0)
			{
				echo '<div style="background:#062327;color:#fff;padding:5px 25px;float:left;margin:5px 20px;">Before you can make a post you must <a href="#add-calendar-box" rel="popup" style="font-weight:bold;color:#fff;" class="no-underline">create</a> a calendar</div>';
			}
		?>
		<div class="clear"></div>
	</div>
</div>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
