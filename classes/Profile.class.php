<?php

class Profile 
{
	private	$Database;		//The PDO object for the database
	
	/**
	 * This variable holds a different id depending on 
	 * the login type. If a user logs in through an API
	 * the $userId variable will be set to that users 
	 * id on the api. If the user logs in through the site
	 * it will be identical to their database id
	 */
	public	$userId;
	
	/**
	 * The database id of the user
	 */
	public	$id;
	
	/**
	 * The email of the user
	 */
	public $email;
	
	/**
	 * The first name of the user
	 */
	public $firstName;
	
	/**
	 * The lastName of the user
	 */
	public $lastName;
	
	/**
	 * The fullname of the user
	 */
	public $fullName;
	
	/**
	 * The username of the user
	 */
	public $username;
	
	/**
	 * The thumbnail of the user
	 */
	public $thumbnail;
	
	/**
	 * The gender of the user
	 */
	public $gender;
	
	/**
	 * The description of the user
	 */
	public $description;
	
	/**
	 * The location of the user
	 */
	public $location;
	
	/**
	 * The type of user registration
	 */
	public $type;
	
	/**
	 * The type of calendar to display on the homepage
	 */
	public $hpCalendar;
	
	/**
	 * The type of feed to display on the homepage
	 */
	public $hpFeed;
	
	/**
	 * The number of calendars created
	 */
	public $numCalendarsCreated;
	
	/**
	 * The number of calendars being followed
	 */
	public $numCalendarsFollowed;
	
	/**
	 * The primary calendar that the user uses
	 */
	public $primaryCalendar;
	
	/**
	 * __construct
	 *
	 * Saves the database object and saves the information of the user
	 *
	 * @param &$Database	object	The database connection
	 * @param $type			String	The type of login
	 * @param $userId		int		The id of the user used with the type of login
	 */
	public function __construct (&$Database, $userId, $type = null)
	{
		$this->Database = $Database;	//The PDO object for the database connection
		$this->type 	= $type;		//The type of login (i.e. facebook, twitter, site)
		$this->userId	= $userId;		//The id of the user
		
		$info = self::getInfo($userId, $type);		//Get the user information
		
		$this->id 					= $info["id"];						//The id of the user
		$this->email 				= $info["email"];					//The email of the user
		$this->firstName			= $info["first_name"];				//The first name of the user
		$this->lastName				= $info["last_name"];				//The last name of the user
		$this->username	 			= $info["username"];				//The username of the user
		$this->thumbnail			= $info["thumbnail"];				//The thumbnail of the user
		$this->gender				= $info["gender"];					//The gender of the user
		$this->description 			= $info["description"];				//Saves the description of the user
		$this->location				= $info["location"];				//The location of the user
		$this->fullName 			= $info["first_name"] . ' ' . $info["last_name"];	//The full name of the user
		$this->numCalendarsCreated 	= $info["num_calendars"];			//The number of calendars the user has created
		$this->numCalendarsFollowed	= $info["num_followed_calendars"];	//The number of calendars being followed
		$this->primaryCalendar		= $info["primary_calendar"];		//The primary calendar of a user
	} //end __construct
	
	/**
	 * getInfo
	 *
	 * Retrives the user information directly from the database 
	 * depending on what the login type was
	 *
	 * @param	$userId		int		The id of the user
	 * @param	$type		String	The login type
	 * @return 	$info		Array	The database info
	 */
	protected function getInfo ($userId, $type = null)
	{
		$info = array();	//Initialize the array
		
		$query = "SELECT * FROM users ";
		
		//Prepare the appropriate query for the database
		switch ($type)
		{
			case 'facebook':
				$query .= "WHERE fb_id = ?";
				break;
			case 'twitter':
				$query .= "WHERE twitter_id = ?";
				break;
			case 'konetch':
				$query .= "WHERE session_key = ?";
				break;
			case 'username':
				$query .= "WHERE username = ?";
				break;
			default:
				$query .= "WHERE id = ?";
		}
		
		//Get information for the database
		$Statement = $this->Database->prepare($query); 
		$Statement->execute(array($userId));
		$info = $Statement->fetch(PDO::FETCH_ASSOC);
		
		return $info;	//The database information queried
	} //end getInfo
	
	/**
	 * Returns the information in an array without a database
	 * connection
	 *
	 * @return $info	Array	The user information
	 */
	public function getUserInfo ()
	{
		$info["id"]						= $this->id;					//The unique id of the user in the database
		$info["user_id"] 				= $this->userId;				//The id of the user includes API id's
		$info["email"]					= $this->email;					//The email of the user
		$info["description"]			= $this->description;			//The description of the user
		$info["location"]				= $this->location;				//The location of the user
		$info["first_name"]				= $this->firstName;				//The first name of the user
		$info["last_name"]				= $this->lastName;				//The last name of the user
		$info["name"]					= $this->fullName;				//The full name of the user
		$info["username"]				= $this->username;				//The username of the user
		$info["type"]					= $this->type;					//The type of user registration
		$info["thumbnail"]				= $this->thumbnail;				//The user's image thumbnail
		$info["gender"]					= $this->gender;				//The gender of the user
		$info["num_calendars"]			= $this->numCalendarsCreated;	//The number of calendars the user has created
		$info["num_followed_calendars"]	= $this->numCalendarsFollowed;	//The number of calendars the user is following
		$info["primary_calendar"]		= $this->primaryCalendar;		//The primary calendar that a user is using
		
		return $info;
	} //end getUserInfo
	
	/**
	 * getNumFollowers
	 *
	 * Returns the number of followers that a user has
	 *
	 * @param	$userId		int		The user to check
	 * @return	$count		int		The number of followers
	 */
	public function getNumFollowers ($userId)
	{
		$Statement = $this->Database->prepare("SELECT id FROM following WHERE user = ?");
		$Statement->execute(array($userId));
		$count = $Statement->rowCount();
		
		return $count;
	} //end getNumFollowers
	
	/**
	 * update
	 *
	 * Updates the correct column in the database with the 
	 * information.
	 *
	 * @param 	$string		String	The information to update
	 * @param	$col		String	The column to update
	 */
	public function update ($string, $col)
	{
		//Check for errors
		if (strlen($string) > 500)
		{
			throw new Exception('Your input must be less than 500 characters');
		}
		else
		{
			$query = 'UPDATE users SET ' . $col . ' = ? WHERE id = ?'; 
			$Statement = $this->Database->prepare($query);
			$Statement->execute(array($string, $this->id));
		}
	} //end setUpdateType
	
	/**
	 * changeRelationship
	 *
	 * Changes the relationship between two users depending on if they
	 * are already following a user or not.
	 *
	 * @param	$profile_id		int		The id of the user being followed/unfollowed
	 * @param	$user_id		int		The id of the user following/unfollowing
	 */
	public function changeRelationship ($profile_id, $user_id)
	{
		$Statement = $this->Database->prepare("SELECT id FROM following WHERE user = ? AND follower = ? LIMIT 1");
		$Statement->execute(array($profile_id, $user_id));
		$count = $Statement->rowCount();
		
		if ($count == 0)
		{
			$Statement = $this->Database->prepare("INSERT INTO following (user, follower) VALUES (?, ?)");
			$Statement->execute(array($profile_id, $user_id));
		}
		else
		{
			$Statement = $this->Database->prepare("DELETE FROM following WHERE user = ? AND follower = ?");
			$Statement->execute(array($profile_id, $user_id));
		}
	} //end changeRelationships
	
	/**
	 * following
	 *
	 * Checks to see whether the logged in user is following the users 
	 * profile or not
	 *
	 * @param	$user		int		The id of the user logged in
	 * @return	bool				True if following
	 */
	public function following ($user, $following_id)
	{
		$Statement = $this->Database->prepare("SELECT id FROM following WHERE user = ? AND follower = ?");
		$Statement->execute(array($user, $following_id));
		$count = $Statement->rowCount();
		
		return ($count == 0) ? false : true;
	} //end following
	
	/**
	 * countUnreadMessages
	 *
	 * Counts the number of unread messages that a user has. If a message's 
	 * unread column in the database is marked as 1 then it will be counted 
	 * as unread
	 *
	 * @return $count	int		The number of unread messages
	 */
	public function countUnreadMessages ()
	{
		$Statement = $this->Database->prepare("SELECT id FROM messages WHERE reciever = ? AND unread = ?");
		$Statement->execute(array($this->id, 1));
		$count = $Statement->rowCount();
		
		return $count;
	} //end countMessages
	
	/**
	 * getMessages
	 *
	 * Returns the last 30 messages that are in the database under the users
	 * name
	 *
	 * @return $messages	Array	The messages from the database
	 */
	public function getMessages ()
	{
		$messages = array();	//Prevent foreach loop error
		$Statement = $this->Database->prepare("SELECT messages.*, users.first_name, users.last_name, users.thumbnail FROM messages INNER JOIN users ON messages.sender_id = users.id WHERE reciever = ? ORDER BY id DESC LIMIT 30");
		$Statement->execute(array($this->id));
		$messages = $Statement->fetchAll();
		
		return $messages;
	} //end getMessages
	
	/**
	 * getMessageTime
	 *
	 * Takes the timestamp on a message and formats it in the correct display
	 *
	 * @param	$timestamp		int		The messages timestamp
	 */
	public function getMessageTime ($timestamp)
	{
		$timestamp = explode(' ', $timestamp);
		$date = explode('-', $timestamp[0]);
		$time = explode(':', $timestamp[1]);
		$time[2] = ($time[0] > 12) ? 'PM' : 'AM';
		if ($time[0] > 12) $time[0] -= 12;
		if ($time[0] == 0) $time[0] = '01';
		
		$timestamp = $date[1] . '/' . $date[2] . '/' . $date[0] . ' ' . $time[0] . ':' . $time[1] . ' ' . $time[2];
		return $timestamp;
	} //end getMessageTime
	
	/**
	 * markRead
	 *
	 * Temporary: Marks all messages as read if a read cookie is instantiated on
	 * click of the message link on the profile
	 */
	public function markRead ()
	{
		if (isset($_COOKIE["read"]) && $_COOKIE["read"] == 1)
		{
			$Statement = $this->Database->prepare("UPDATE messages SET unread = 0 WHERE reciever = ?");
			$Statement->execute(array($this->id));
			setcookie('read', 0, time()-3600);
		}
	} //end markRead
	
	/**
	 * addUserLocation
	 *
	 * Updates the database with the user's current location
	 *
	 * @param $location		String	The user's location
	 */
	public function addUserLocation ($location)
	{
		$Statement = $this->Database->prepare("UPDATE users SET location = ? WHERE id = ?");
		$Statement->execute(array($location, $this->id));
	} //end addUserLocation
	
	/**
	 * addCalendarType
	 *
	 * Updates the user's preference for the type of calendar
	 * displayed on their homepage
	 *
	 * @param	$type	String	The value for the database
	 */
	public function addCalendarType ($type)
	{
		$Statement = $this->Database->prepare("UPDATE users SET hp_calendar_type = ? WHERE id = ?");
		$Statement->execute(array($type, $this->id));
	} //end addCalendarType
	
	/**
	 * addFeedType
	 *
	 * Updates the user's preference for the type of news feed
	 * displayed on their homepage
	 *
	 * @param	$type	String	The value for the database
	 */
	public function addFeedType ($type)
	{
		$Statement = $this->Database->prepare("UPDATE users SET hp_feed_type = ? WHERE id = ?");
		$Statement->execute(array($type, $this->id));
	} //end addFeedType
	
	/**
	 * followingCalendar
	 *
	 * Checks to see if a user is following a calendar or not
	 *
	 * @param	$calendarId		int		The id of the calendar
	 * @param	$userId			int		The id of the user to check
	 * @return 	bool					True if following, false if not
	 */
	public function followingCalendar ($calendarId, $userId)
	{
		$Statement = $this->Database->prepare("SELECT id FROM following_calendar WHERE user_id = ? AND calendar_id = ?");
		$Statement->execute(array($userId, $calendarId));
		$rowCount = $Statement->rowCount();
		
		return $rowCount;
	} //end followingCalendar
	
	/**
	 * setPrimaryCalendar
	 *
	 * Updates the user's profile with their primary calendar
	 *
	 * @param	$calendarId		int		The id of the calendar
	 * @return	bool
	 */
	public function setPrimaryCalendar ($calendarId)
	{
		$Statement = $this->Database->prepare("UPDATE users SET primary_calendar = ? WHERE id = ?");
		$Statement->execute(array($calendarId, $this->id));
		
		return true;
	} //end setPrimaryCalendar
} //end Profile

?>
