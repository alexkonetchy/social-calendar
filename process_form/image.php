<?php 
 	require '../config.php';
 	require $baseDir . 'includes/standard_page_load.php';
 	
 	//If a user is logged in
	if ($_SESSION["in"] == true)
	{
		/**
		 * Processes an image upload
		 */
		if(isset($_FILES['image']) && isset($_POST["image-upload"])) 
		{
			$Image = new Image;
			$Image->uploadTo = $baseDir . 'images/posts/';
			$uploadedImage = $Image->upload($_FILES['image']);
			if($uploadedImage) 
			{
				    $path = $Image->resize();
			   try
			   {
				   //Set the appropriate hour
					if ($_POST["half"] == 'PM')
					{
						$hour = ($_POST["image-hour"] == 12) ? $hour = 12 : $_POST["image-hour"] + 12;
					}
					else 
					{
						$hour = $_POST["image-hour"];
					}
					
				    $Image->setPath($path, $url);
				    $Image->setDescription($_POST["image-description"]);
				    $Image->setLocation($_POST["image-location"]);
				    $Image->setMonth($_POST["image-month"]);
				    $Image->setDay($_POST["image-day"]);
				    $Image->setYear($_POST["image-year"]);
				    $Image->setHour($_POST["image-hour"]);
				    $Image->setMinute($_POST["image-minute"]);
				    $Image->setPrivacy($_POST["image-privacy"]);
				    $Image->query($user["id"], $user["username"], $user["name"]);
				    
				    echo '1';
			    }
			    catch (Exception $e)
			    {
				    echo $e->getMessage();
			    }
			}
		}
	}

?>
