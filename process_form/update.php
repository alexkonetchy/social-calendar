<?php
 	require '../config.php';
 	require $baseDir . 'includes/standard_page_load.php';

	//If the form was submitted
	if (isset($_POST["update"]))
	{
		if (!empty($_FILES["image"]["name"]))
		{
			$Image = new Image($Database);
			$Image->uploadTo = $baseDir . 'images/posts/';
			$uploadedImage = $Image->upload($_FILES['image']);
			if($uploadedImage) 
			{
				$Image->newWidth = 600;
				$Image->newHeight = 400;
				$path = $Image->resize();
				try
				{	
					$Image->setPath($path, $url, $baseDir);
					$Image->setCalendarId($_POST["calendar_id"]);
					$Image->setContent($_POST["content"]);
					$Image->setTags($_POST["content"]);
					$Image->setLocation($_POST["location"]);
					$Image->setMonth($_POST["month"]);
					$Image->setDay($_POST["day"]);
					$Image->setYear($_POST["year"]);
					$Image->query($user["id"]);
					
					header("Location: " . htmlspecialchars($_SERVER["HTTP_REFERER"]));
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
				}
			}
		}
		else
		{
			$Update = new Update($user["id"], $Database);
			
			try
			{
				echo $_POST["month"] . '-' . $_POST["day"];
				$Update->setContent($_POST["content"]);
				$Update->setTags($_POST["content"]);
				$Update->setCategory("text");
				$Update->setMonth($_POST["month"]);
				$Update->setDay($_POST["day"]);
				$Update->setYear($_POST["year"]);
				$Update->setLocation($_POST["location"]);
				$Update->setCalendarId($_POST["calendar_id"]);
				$Update->create();
				
				header("Location: " . htmlspecialchars($_SERVER["HTTP_REFERER"]));
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
		}
	}

?>
