<?php

class Update 
{
	protected $content;			//The content of the update
	protected $tags;			//The string of tags
	protected $mentioned;		//The users the were mentioned in the post
	protected $category;		//The category of the update
	protected $location;		//The location of the status
	protected $month;			//The month the status is given for
	protected $day;				//The day the status is given for
	protected $year;			//The year the status is given for
	protected $time;			//The time the status is given for
	protected $userId;			//The id of the user
	protected $embedCode;		//The code to embed an object in html
	protected $calendarId;		//The id of the calendar (0 for the public calendar)
	private $Database;
	
	
	/**
	 * constructor
	 *
	 * Initialize the database connection and save the appropriate
	 * information for the update
	 */
	public function __construct ($userId, &$Database)
	{
		$this->Database 	= $Database;
		$this->userId		= $userId;	
	} //end __construct
	
	public function setCalendarId ($id = 0)
	{
		$this->calendarId = $id;
	} //end setCalendarId
	
	public function setMonth ($month)
	{
		if (!is_numeric($month))
		{
			throw new Exception('You must have a valid date');
		}
		else if (strlen($month) != 2)
		{
			if ($day < 10)
			{
				$this->month = '0' . $month;
			}
			else
			{
				throw new Exception('You must have a valid date');
			}
		}
		else if ($month < 1 || $month > 12)
		{
			throw new Exception('You must have a valid date');
		}
		else
		{
			$this->month = $month;
		}
	} //end setMonth
	
	public function setDay ($day)
	{
		if (!is_numeric($day))
		{
			throw new Exception('You must have a valid date');
		}
		else if (strlen($day) != 2)
		{
			if ($day < 10)
			{
				$this->day = '0' . $day;
			}
			else
			{
				throw new Exception('You must have a valid date');
			}
		}
		else if ($day < 1 || $day > 31)
		{
			throw new Exception('You must have a valid date');
		}
		else
		{
			$this->day = $day;
		}
	} //end setDay
	
	public function setYear ($year)
	{
		if (!is_numeric($year))
		{
			throw new Exception('You must have a valid date');
		}
		else if (strlen($year) != 4)
		{
			throw new Exception('You must have a valid date');
		}
		else
		{
			$this->year = $year;
		}
	} //end setYear
	
	/**
	 * setContent
	 *
	 * Saves the content of the status
	 */
	public function setContent ($content)
	{
		if (strlen($content) > 1000)
		{
			throw new Exception('Your status must be less than 1000 characters.');
		}
		else if (empty($content))
		{
			throw new Exception('You must submit a status');
		}
		
		$this->content = $content;
	} //end setContent
	
	/**
	 * setTags
	 *
	 * Retrives the tags from the post and saves the to the 
	 * database
	 *
	 * @param	$content	String	The status to get the tags from
	 */
	public function setTags ($content)
	{
		preg_match_all("/(#\w+)/", $content, $array);
		
		$limit = count($array);
		
		$tags = '';
		for ($count = 0; $count < $limit; $count++)
		{
			$tags .= $array[0][$count] . ',';
		}
		
		$tags = rtrim($tags, ','); //Get rid of last comma
		
		$this->tags = $tags;
	} //end setTags
	
	protected function setMentionedUsers ($content)
	{
		preg_match_all("/(^|\s)@([a-z0-9._]+)/i", $content, $array); 
		
		$limit = count($array);
		
		$mentioned = '';
		for ($count = 0; $count < $limit; $count++)
			$mentioned .=  substr($array[0][$count], 1) . ',';
			
		$mentioned = rtrim($mentioned, ','); //Get rid of last comma
		
		return $mentioned;
	}
	/**
	 * setCategory
	 *
	 * Saves the category of the status
	 */
	public function setCategory ($category)
	{
		$this->category = $category;
	} //end setType
			
	/**
	 * setLocation
	 *
	 * Saves the location
	 */
	public function setLocation ($location)
	{
		if (strlen($location) > 250)
		{
			throw new Exception('Your location must be less than 250 characters');
		}
		
		$this->location = $location;
	} //end setLocation
	
	/**
	 * setEmbedCode
	 *
	 * The html code to embed an object in the page
	 */
	public function setEmbedCode ($code)
	{
		$this->embedCode = $code;
	}
	
	/**
	 * create
	 *
	 * Queries the database and saves the information
	 */
	public function create ()
	{
		if (self::verifyPostPerDay() == false)
		{
			throw new Exception('You are only allowed to post once per day. Sorry :/');
		}
		
		self::verifyPermission();
		
		if ($this->calendarId != 0)
		{
			self::updatePostCount();	//Updates the post count for the calendar
		}
		
		$mentioned = self::setMentionedUsers($this->content);
		
		//Saves all of the data in the database
		$Statement = $this->Database->prepare("INSERT INTO statuses (user_id, calendar_id, month, day, year, content, tags, mentioned_users, category, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$Statement->execute(array(	$this->userId,
									$this->calendarId,
									$this->month, 
									$this->day, 
									$this->year, 
									$this->content, 
									$this->tags, 
									$mentioned,
									$this->category, 
									$this->location ));
	} //end create
	
	protected function verifyPermission ()
	{
		$Statement = $this->Database->prepare("SELECT user_id FROM calendars WHERE id = ?");
		$Statement->execute(array($this->calendarId));
		$calendar = $Statement->fetch(PDO::FETCH_ASSOC);
		
		if ($calendar["permission"] == 0)
			return true;
		else if (($calendar["user_id"] != $this->userId) && ($calendar["permission"] == 1))
			throw new Exception ('You\'re not allowed to update this calendar');
		else 
			return true;
			
	} //end verifyPermission
	
	protected function verifyPostPerDay ()
	{
		$Statement = $this->Database->prepare("SELECT id FROM statuses WHERE calendar_id = ? AND month = ? AND day = ? AND year = ? AND user_id = ?");
		$Statement->execute(array($this->calendarId, $this->month, $this->day, $this->year, $this->userId));
		$rowCount = $Statement->rowCount();
		
		return ($rowCount == 0) ? true : false;
	} //end verifyPostPerDay
	
	protected function updatePostCount ()
	{
		$Statement = $this->Database->prepare("UPDATE calendars SET num_posts = num_posts + 1 WHERE id = ?");
		$Statement->execute(array($this->calendarId));
	} //end updatePostCount
} //end Update

?>
