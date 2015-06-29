<?php

/**
 * config.php - CONFIGURATION  FILE
 *
 * This file will hold the database login information as well as the site
 * name, description, directories, etc.
 *
 * The file should be included in any files that want to use the variables
 */
 
/**
* LAST EDITED: 	Alex Konetchy
* 				3-3-2013
*
* Please place any initials and the date next to any changes or additions 
* made that were not done by Alex Konetchy
*/

	/**Database Information**/
	
	$dbhost = 'localhost';
	$dbname = 'calender';
	$dbuser = 'root';
	$dbpass = ''; //p4V75M3hcFns9pYx
	
	try
	{
		$Database = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
		$Database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e)
	{
		echo 'We\'re sorry. There was an unexpected error. Please try refreshing the page.';
	}
	
	/**Site Information**/
	
	$siteTitle 			= 'captchur';							//The title prefix
	$siteDescription 	= 'Never miss a moment.';				//The description of the site
	$url				= 'http://localhost';					//The base url of the site
	$baseDir			= 'C:\\xampp\\htdocs\\'; 				//The base directory of the site /usr/local/httpd/AlexKonetchy/www.konetch.com/--*/
	
	/**Site Settings**/
	
	$salt 				= 'rtwert&(ag89hyeyh7*&^&8ewbyausbfagh8*&*&f';		//The stadard salt for hashes
	error_reporting(E_ALL ^ E_NOTICE);										//Default error reporting for the site. Should be set to 0 for production
	
	/**AUTOLOAD FUNCTION**/
	
	function __autoload ($class)
	{
		require $baseDir . 'classes/' . $class . '.class.php';
	} //end __autoload

?>
  
