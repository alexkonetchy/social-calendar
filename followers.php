<?php
	#####################################################
	/**
	 * The page settings
	 */
	require 'config.php';									//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';	//Loads most common features (i.e. Facebook api)
	$title = 'Followers';									//The page title
	$Friends = new Friends($user["id"], $Database);			//Instantiate the friends class
	
	#####################################################
	/**
	 * Start the html of the page with the page header
	 */
	require $baseDir . 'includes/templates/page_header.php';	//The html for the heading of the page
?>

	<div class="wrapper">
		<?php require $baseDir . 'includes/templates/left_menu.php'; /*Display the default left menu*/ ?>
			
		<!--START TIMELINE-->
		<div class="container">
			<div class="top-heading">
				Followers
			</div>	
			<div style="text-align:left;width:96%;margin:0 auto;">
				<?php 
					#####################################################
					/**
					 * Display all of the people following the logged in user
					 */
					foreach ($Friends->getFollowers() as $follower): 
				?>
				
					<div class="feed-box-title" style="margin-bottom:6px;">
						<a href="profile.php?id=<?php echo $follower["follower"]; ?>" style="margin:0"><img src="<?php echo $follower["thumbnail"]; ?>" /></a>
						<a href="profile.php?id=<?php echo $follower["follower"]; ?>"><?php echo $follower["first_name"] . ' ' . $follower["last_name"]; ?></a>
						<div class="clear"></div>
					</div>
					
				<?php endforeach; ?>
			</div>
		</div>	

		<div class="container">
			<div class="top-heading">
				Following
			</div>	
			<div style="text-align:left;width:96%;margin:0 auto;">
				<?php 
					#####################################################
					/**
					 * Display all of the people the loggeed in user is following
					 */
					foreach ($Friends->getFollowing() as $following): 
				?>
				
					<div class="feed-box-title" style="margin-bottom:6px;">
						<a href="profile.php?id=<?php echo $following["user"]; ?>" style="margin:0"><img src="<?php echo $following["thumbnail"]; ?>" /></a>
						<a href="profile.php?id=<?php echo $following["user"]; ?>"><?php echo $following["first_name"] . ' ' . $following["last_name"]; ?></a>
						<div class="clear"></div>
					</div>
					
				<?php endforeach; ?>
			</div>
		</div>
<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
