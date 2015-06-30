<?php

require_once 'Calendar.class.php';

class UserCalendars extends Calendar
{
	/**
	 * The PDO object to connect with the database
	 */
	private $Database;
	
	public function __construct($month = null, $year = null, &$Database)
	{
		$this->Database = $Database;			//Instantiate the PDO Object
		
		parent::__construct($month, $year);		//Call the parent constructor
	} //end __construct
	
	public function getUserCalendars ($userId)
	{
		$calendars = array();
		
		$Statement = $this->Database->prepare("SELECT * FROM calendars WHERE user_id = ? ORDER BY id DESC");
		$Statement->execute(array($userId));
		$calendars = $Statement->fetchAll();
		
		return $calendars;
	} //end getUserCalendars
	
	public function getCalendar ($id)
	{
		$Statement = $this->Database->prepare("SELECT calendars.*, users.first_name, users.last_name, users.username FROM calendars INNER JOIN users ON calendars.user_id = users.id WHERE calendars.id = ?");
		$Statement->execute(array($id));
		$calendar = $Statement->fetch(PDO::FETCH_ASSOC);
		
		return $calendar;
	} //end getCalendar
	
	public function getFollowing ($userId)
	{
		$calendars = array();
		
		$Statement = $this->Database->prepare("SELECT calendars.*, following_calendar.calendar_id FROM following_calendar INNER JOIN calendars ON following_calendar.calendar_id = calendars.id WHERE following_calendar.user_id = ? ORDER BY following_calendar.id DESC");
		$Statement->execute(array($userId));
		$calendars = $Statement->fetchAll();
		
		return $calendars;
	} //end getFollowing
	
	public function getTop20 ()
	{
		$calendars = array();
		
		$Statement = $this->Database->prepare("SELECT calendars.*, following_calendar.calendar_id, count(following_calendar.calendar_id) AS 'number' FROM following_calendar INNER JOIN calendars ON calendars.id = following_calendar.calendar_id GROUP BY following_calendar.calendar_id ORDER BY calendars.num_followers DESC LIMIT 20");
		$Statement->execute(array($userId));
		$calendars = $Statement->fetchAll();
		
		return $calendars;
	}
	
	public function getLatest ()
	{
		$calendars = array();
		
		$Statement = $this->Database->prepare("SELECT * FROM calendars ORDER BY id DESC LIMIT 20");
		$Statement->execute(array($userId));
		$calendars = $Statement->fetchAll();
		
		return $calendars;
	}
	
	public function getNumCalendars ()
	{
		$Statement = $this->Database->prepare("SELECT id FROM calendars ORDER BY id DESC LIMIT 1");
		$Statement->execute();
		$row = $Statement->fetch(PDO::FETCH_ASSOC);
		
		return $row["id"];
	} //end getNumCalendars
	
	public function getCalendarImages ($calendarId)
	{
		$images = array();
		
		$Statement = $this->Database->prepare("SELECT image_location, month, year, id FROM statuses WHERE calendar_id = ? AND category = 'image' ORDER BY id DESC LIMIT 10");
		$Statement->execute(array($calendarId));
		$images = $Statement->fetchAll();
				
		return $images;
	}
	
} //end UserCalendars

?>
