<?php
	ob_start();			//Start the buffer
	session_start();	//Start the session
	
	#########################################################################################
    #																						#
	# If a user is logged in through facebook							                    #
	#																						#
	#########################################################################################
	
	if ($_SESSION["login_type"] == 'facebook')
	{
		require $baseDir . 'includes/fb-php-sdk/src/facebook.php';	//Facebook class
		$Facebook = new Facebook($fbConfig);						//Initialize Facebook
		
		$fb = $Facebook->getUser();									//Get the user
		
		//Authorize the user
		if ($fb)
		{
	  		try 
	  		{
	    		$user = $Facebook->api('/me');						//Saves ther user's inforamtion
	  		} 
	  		catch (FacebookApiException $e) 
	  		{
	    		echo 'There was an unexpected error. Please contact us.';
	    		$fb = null;
	  		}
		}
		require $baseDir . 'includes/redirect.php';	//Redirect to login if the user isn't logged in
		
		$Profile = new Profile($Database, $user["id"], $_SESSION["login_type"]);	//Instantiate the profile class
		$user = $Profile->getUserInfo();											//Get information about the user
	}
	
	#########################################################################################
    #																						#
	# If a user is logged in through datehitter							                    #
	#																						#
	#########################################################################################
	
	if ($_SESSION["login_type"] == 'konetch')
	{
		$Login = new Login($Database);
		if ($Login->validate($_SESSION["session_key"]))
		{
			$Profile = new Profile($Database, $_SESSION["session_key"], $_SESSION["login_type"]);	//Instantiate the user class with the 
			$user = $Profile->getUserInfo();
		}
	} 

	if (!isset($publicPage)) require $baseDir . 'includes/redirect.php';	//If this isn't a public page, redirect to login
?>
