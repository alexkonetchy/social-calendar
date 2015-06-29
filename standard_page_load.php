<?php

###################
#
# Author: Alex Konetchy
# Date: January 20th, 2013
# Description:
#
#		The standard page load for most front end pages on the web application. This file starts
# 		the session and output buffer, checks to see if a user is logged in or not, and if a session 
# 		for their login is present, then the page gathers the information and saves it in the variable 
#		$user.
#
###################

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
	# If a user is logged in through konetch							                    #
	#																						#
	#########################################################################################
	
	if ($_SESSION["login_type"] == 'konetch')
	{
		$Login = new Login($Database);					//Instantiate the login class
		if ($Login->validate($_SESSION["session_key"]))	//Validate the session key
		{
			$Profile = new Profile($Database, $_SESSION["session_key"], $_SESSION["login_type"]);	//Instantiate the user class with the session key
			$user = $Profile->getUserInfo();	//Get the user's information from the database
		}
	} 

	## the variable, $publicPage, must be set before this file is included
	if (!isset($publicPage)) require $baseDir . 'includes/redirect.php';	//If this isn't a public page, redirect to login
?>
