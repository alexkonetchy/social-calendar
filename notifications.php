<?php
	#####################################################
	/**
	 * The page settings
	 */
	require 'config.php';									//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';	//Loads most common features (i.e. Facebook api)
	$title = 'Notifications';								//The page title
	$Feed = new Feed($Database);							//The feed class
	
	#####################################################
	/**
	 * The html for the page. Starting with the standard header
	 */
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>

	<div class="wrapper">
		<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>

		<!--User Notifications-->
		<div class="container">
			<div class="top-heading">Latest Notifications</div>
			
			<?php
				$notifyCount = 0;	//Initial count of the number of notifications
				foreach ($Feed->getNotifications($user["username"]) as $status)	//Display the notifications
				{
					require $baseDir . 'includes/templates/mentioned_box.php';
					$notifyCount++;
				}
				
				//If there are no notifications
				if ($notifyCount == 0) echo '<div style="color:#666;">Oops. You don\'t have any notifications yet.</div>';	
			?>
		</div>
		</div class="clear"></div>
<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
