<?php
	#####################################################
	/**
	 * The standard page settings
	 */
	$publicPage = true;										//Bool to confirm it is a public page
	require 'config.php';									//Configuration settings	
	require $baseDir . 'includes/standard_page_load.php';	//Loads most common features (i.e. Facebook api)
	 	
	//Handles the link to get the user information
	if (strstr($_SERVER["REQUEST_URI"], '/u/'))						//If the profile is accesed using the username
	{
		$userId = explode('/u/', $_SERVER["REQUEST_URI"]);			//Get the username from the url
		$Profile = new Profile($Database, $userId[1], 'username');	//Instantiate profile class based on username
	}
	else															//If the profile is accessed using the profile id
		$Profile = new Profile($Database, $_GET["id"]);				//Instantiate profile class based on user id
	

	$profile = $Profile->getUserInfo(); //Profile information from the database
	$title = $profile["name"]; 			//Change page title to user's name
	
	#####################################################
	/**
	 * Processes all of the page's form submissions
	 */
	 
	//If the user is trying to follow the profile
	if ($_GET["action"] == 'change_relationship')
	{
		$Profile->changeRelationship($profile["id"], $user["id"]);
		header('Location: profile.php?id=' . $profile["id"]);		//Redirect to prevent form resubmission
	}
	
	//Process the update profile form
	if (isset($_POST["edit_profile"]))
	{
		try
		{
			$Profile->update($_POST["description_text"], 'description');	//The user's description
			$Profile->update($_POST["gender"], 'gender');					//The user's gender
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		
		header("Location: " . $_SERVER["REQUEST_URI"]);		//Redirect to prevent form resubmission
	}
					
	//Process the upload profile image form
	if (isset($_POST["upload_profile_pic"]))
	{
		if(isset($_FILES['default-image'])) 
		{
			$Image = new Image($Database);
			$Image->uploadTo = $baseDir . 'images/profiles/';
			$uploadedImage = $Image->upload($_FILES['default-image']);
			if($uploadedImage) 
			{
				    $Image->newWidth = 50;
				    $Image->newHeight = 50;
				    $path = $Image->resize();
			   try
			   {	
				    $Image->setPath($path, $url, $baseDir);
				    $thumbnailUrl = $Image->getPath();
				    
				    $Statement = $Database->prepare("UPDATE users SET thumbnail = ? WHERE id = ?");
				    $Statement->execute(array($thumbnailUrl, $user["id"]));
				    
				    header("Location: " . $_SERVER["REQUEST_URI"]);
			    }
			    catch (Exception $e)
			    {
				    echo $e->getMessage();
			    }
			}
		}
	}
	
	//Process the message reply form
	if (isset($_POST["send_reply" . $messageCount]))
	{	
		$Message = new Message($user["id"], $Database);	//Instantiate the message class
			
		try
		{
			$Message->setReciever($_POST["sid" . $messageCount]);
			$Message->setText($_POST["message_text" . $messageCount]);
			$Message->send();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		
		header("Location: " . $_SERVER["REQUEST_URI"]);	
	}
						
	#####################################################
	/**
	 * Displays the html of the page based on the page settings
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
		<div class="profile-display-name">
			<img src="<?php echo $profile["thumbnail"]; ?>" style="width:60px;border:3px #fff solid;" />
			<br/>
			<span style="font-size:25px;"><?php echo $profile["name"]; ?></span> 
			<br/>
			<em><?php echo $profile["description"]; ?></em>
			<br/>
			<?php if ($user["id"] == $profile["id"]): /*If the user is viewing there own profile*/ ?>
			
				<div class="profile-display-subheading">
						<a href="javascript:slide('edit-profile');">Edit Profile</a> 
				</div>
			
			<?php endif; ?>
		</div>

		<div class="profile-box profile-mini-nav">
			<?php echo $Profile->getNumFollowers($profile["id"]); ?> followers 
		</div>
		
		<?php if ($user["id"] == $profile["id"]): /*If the user is viewing their own profile*/ ?>
			
			<!--Edit Profile Hidden Forms-->
			<div class="slide-box dashboard-box" id="edit-profile">
				<!--Edit form-->
				<div class="profile-box" style="border:0;float:left;width:385px;border-right:1px #999 dotted;background:#f1f1f1;">
					<form action="#" method="post">
						<p><strong>Description</strong></p>
						<textarea name="description_text" style="width:345px;height:50px;"><?php echo $user["description"]; ?></textarea>
						<br/>
						<input type="submit" name="edit_profile" class="a-button" style="margin-top:5px;" value="Update"/>
					</form>
				</div>
				<!--Upload profile picture form-->
				<div class="profile-box" style="border:0;float:left;padding-left:20px;">
					<p><strong>Upload a profile picture</strong></p>
					<form action="#" method="post" enctype="multipart/form-data">
						<input type="file" name="default-image" />
						<br/>
						<input type="submit" name="upload_profile_pic" class="a-button" style="margin-top:5px;" value="Upload" />
					</form>
				</div>
				<div class="clear"></div>
			</div>
			<!--End Edit Profile Hidden Forms-->
			
		<?php endif; ?>
		
		<strong>Calendars (<?php echo $profile["num_calendars"]; ?>)</strong>
		<div style="margin-top:5px;">
			<?php 
				if (!isset($UserCalendars)) $UserCalendars = new UserCalendars(date('m'), date('Y'), $Database);
				
				$calendarCount = 0;
				
				//Display a list of the calendars created by the user
				foreach ($UserCalendars->getUserCalendars($profile["id"]) as $calendar)
				{
					require $baseDir . 'includes/templates/calendar_preview_box.php';
					$calendarCount++;
				}
				
				if ($calendarCount == 0) echo '<br/><em>This user hasn\'t created any calendars yet</em>';
			?>
			<div class="clear"></div>
		</div>
		
	</div>
	<div class="clear"></div>
</div>

<?php
	require $baseDir . 'includes/templates/page_footer.php';		//The html for the footer of the page
?>
