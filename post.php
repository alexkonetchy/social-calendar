<?php
	#####################################################
	/**
	 * Page configuration settings
	 */
	$publicPage = true;											//The page can be viewed by the public
	require 'config.php';										//Configuration settings
	require $baseDir . 'includes/standard_page_load.php';		//Loads most common features (i.e. Facebook api)
	
	//Title of the page
	$title = date('F jS, Y', mktime(0,0,0,$status["month"],$status["day"],$status["year"])) . ' / ' . $status["first_name"] . ' ' . $status["last_name"]; 
	
	#####################################################
	/**
	 * Get the information about the post
	 */
	$Feed = new Feed ($Database);			//Start the feed class
	$status = $Feed->getPost($_GET["id"]);	//Get the information about the post
	
	#####################################################
	/**
	 * Processing of all form submissions
	 */
	if (isset($_POST["add_comment"]))
	{
		$AddComment = new AddComment($Database);		//Initialize the class
		$AddComment->setPostId($status["id"]);			//Set the id of the post being commented on
		$AddComment->setUser($user["id"]);				//Set the id of the user commenting on the post
		$AddComment->setComment($_POST["comment"]);		//Set the content of the comments
		$AddComment->add();								//Query the database to save the comment
		
		header("Location: " . $_SERVER["REQUEST_URI"]);	//Redirect to prevent form submission
	}
	
	#####################################################
	/**
	 * Start the actual html of the page by including the header
	 */
	if (!empty($user))	//If the user is logged in
		require $baseDir . 'includes/templates/page_header.php';
	else				//If the user isn't logged in
	 	require $baseDir . 'includes/templates/public_page_header.php';
?>

	<div class="wrapper">
		<?php 
			/**
			 * Display the left menu
			 */
			if (empty($user)) 	//If a user is not logged in
				require $baseDir . 'includes/templates/public_left_menu.php';
			else				//If a user is logged in
				require $baseDir . 'includes/templates/left_menu.php'; 
		?>
		
		<div class="content-wrapper">
			<div class="post-template">
				<a href="<?php echo $url; ?>/u/<?php echo $status["username"]; ?>" style="margin:0;position:absolute;top:0;left:0;"><img src="<?php echo $status["thumbnail"]; ?>" style="width:50px;height:50px;border-bottom:1px #aaa solid;border-right:1px #aaa solid;" /></a>
				<div style="position:absolute;top:0;right:0;font-size:1.4em;"><span class="orange"><?php echo date('l', mktime(0,0,0,$status["month"],$status["day"],$status["year"])); ?></span> <?php echo date('F jS, Y', mktime(0,0,0,$status["month"],$status["day"],$status["year"])); /*Displays the textual day of the week, textual month, day, suffix and year*/ ?></div>
				
				<div class="status-popup-title">
					<a href="<?php echo $url; ?>/u/<?php echo $status["username"]; ?>"><?php echo $status["first_name"] . ' ' . $status["last_name"]; ?></a>
				</div>

				<div style="position:relative;">
					<div style="position:absolute;top:-8px;right:176px;background:#fff;font-size:11px;color:#888;padding:0 5px;"><?php echo $status["location"]; ?></div>
					<?php if ($status["username"] == $user["username"]): /*If the user is logged in*/ ?>
					<?php if ($Feed->checkPostFeatured($status["id"]) == false): /*If the status isn't featured*/ ?>
					
						<a href="javascript:;" class="make_featured" id="pmid_<?php echo $status["id"]; ?>" style="position:absolute;top:-8px;right:75px;background:#fff;font-size:11px;padding:0 5px;text-decoration:none;">Make Featured</a>
					
					<?php else: /*If the status is featured*/ ?>
					
						<a href="javascript:;" class="featured_label make_featured" id="pmid_<?php echo $status["id"]; ?>" style="position:absolute;top:-8px;right:75px;background:#fff;font-size:11px;padding:0 5px;text-decoration:none;">Featured</a>
					
					<?php endif; /*type of featured link to display*/ ?>
					<a href="javascript:;" class="delete_post" id="dp_<?php echo $status["id"]; ?>" style="position:absolute;top:-8px;right:20px;background:#fff;font-size:11px;padding:0 5px;text-decoration:none;">Delete</a>
					<?php endif; /*The user is logged in*/ ?>
					
					<div class="post-content">
					<?php 
						require $baseDir . 'includes/templates/full_status_content.php';
						
						if (!empty($user)):	//If a user is logged in
					?>
						<div style="margin-top:11px;font-size:11px;" id="comment">
							<a href="javascript:toggle('add-comment');" style="text-decoration:none;">+ Add Comment</a>
							|
							<a href="<?php echo $url; ?>/calendar.php?cid=<?php echo $status["calendar_id"]; ?>&m=<?php echo $status["month"]; ?>&y=<?php echo $status["year"]; ?>" style="color:#333;font-weight:bold;text-decoration:none;">View on Calendar</a>
						</div>
						<form action="#" method="post" style="display:none;" id="add-comment">
							<input type="text" name="comment" placeholder="comment... (250 characters or less)" style="width:50%;" class="post-input">
							<input type="submit" name="add_comment" style="display:none;" value="Post" />
						</form>

						<?php endif; ?>
						<?php
							#######################################################
							/**
							 * Display the comments for the post
							 */
							$Comment = new Comment($Database);
							
							foreach ($Comment->getComments($status["id"]) as $comment):
						?>
							<div class="post-comment">
								<div style="line-height:20px;margin-bottom:5px;color:#000;"><img src="<?php echo $comment["thumbnail"]; ?>" style="width:20px;float:left;border-radius:2px;" /> &nbsp;<strong><a href="<?php echo $url; ?>/profile.php?id=<?php echo $comment["user_id"]; ?>" style="color:#000;text-decoration:none;"><?php echo $comment["first_name"] . ' ' . $comment["last_name"]; ?></a></strong><div class="clear"></div></div>
								<?php echo nl2br(htmlspecialchars($comment["comment"])); ?>
							</div>
						<?php endforeach; /*End displaying comments*/ ?>
						
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
