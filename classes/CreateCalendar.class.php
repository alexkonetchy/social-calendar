<?php

require_once 'Calendar.class.php';

class CreateCalendar extends Calendar
{
	/**
	 * The PDO object for the database
	 */
	private $Database;
	
	/**
	 * The calendar title
	 */
	protected $title;
	
	/**
	 * The description of the calendar
	 */
	protected $description;
	
	/**
	 * The user id of whoever created the calendar
	 */
	private $userId;
	
	public function __construct(&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	public function setTitle ($title)
	{
		$this->title = $title;
	} //end setTitle
	
	public function setDescription ($description = 'No Description')
	{
		$this->description = $description;
	} //end setDescription
	
	public function setUserId ($userId)
	{
		$this->userId = $userId;
	} //end setUserId
	
	public function create ()
	{
		$Statement = $this->Database->prepare("INSERT INTO calendars (title, description, user_id) VALUES (?, ?, ?)");
		$Statement->execute(array($this->title, $this->description, $this->userId));
		
		$Statement = $this->Database->prepare("UPDATE users SET num_calendars = num_calendars + 1 WHERE id = ?");
		$Statement->execute(array($this->userId));
		
	} //end create	
} //end CreateCalendar

?>
