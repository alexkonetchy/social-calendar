<?php

require_once 'Feed.class.php';

class Timeline extends Feed
{
	private $Datbase;
	public $calendarId;
	public $userId;
	
	public function __construct (&$Database)
	{
		parent::__construct();
	}
	
	public function getRandomDates ()
	{
		$Statement = $this->Database
	} //end getRandomDates
} //end Timeline

?>
