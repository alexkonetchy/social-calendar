<?php

class Feed 
{
	/**
	 * The PDO database object
	 */
	private $Database;

	/**
	 * The default beginning to most sql prepared statements. This part of
	 * of the statement selects the user information from the users table
	 */
	protected $sqlStart = "SELECT calendars.*, statuses.*, users.first_name, users.last_name, users.thumbnail, users.username FROM statuses INNER JOIN users ON statuses.user_id = users.id INNER JOIN calendars ON calendars.id = statuses.calendar_id ";
									
	/**							
	 * constructor
	 *
	 * Connects to the database
	 */
	public function __construct(&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	public function getPost ($postId)
	{
		$sql = $this->sqlStart . "WHERE statuses.id = ? ORDER BY statuses.id DESC";
	
		$Statement = $this->Database->prepare($sql);
		$Statement->execute(array($postId));
		$post = $Statement->fetch(PDO::FETCH_ASSOC);
		
		return $post;
	} //end getPost
	
	public function checkPostFeatured ($postId)
	{
		$sql = "SELECT id FROM featured_statuses WHERE status_id = ?";
		
		$Statement = $this->Database->prepare("SELECT id FROM featured_statuses WHERE status_id = ?");
		$Statement->execute(array($postId));
		$rowCount = $Statement->rowCount();
		
		return ($rowCount == 0) ? false : true;
	} //end checkPostFeatured
	
	/**
	 * getPublicFeed
	 *
	 * Retrieves the necessary data from the database for a given month and
	 * year. Returns it as an array that can be cycled through.
	 *
	 * @param	$month		int		The month to display the feed for
	 * @param 	$year		int		The year to display the feed for
	 * @return	$feed		array	The data retrieved from the database organized
	 *								by column name
	 * @author	Alex Konetchy
	 */
	public function getPublicFeed ($month, $year)
	{
		$sql = $this->sqlStart . "WHERE statuses.month = ? AND statuses.year = ? ORDER BY statuses.id DESC";
		
		$feed = array(); //Initialize an empty array
		$feed = self::executeSql($sql, array($month, $year));
		return $feed;
	} //end getPublicFeed
	
	/**
	 * getUserCalendarFeed
	 *
	 * Retrieves all of the posts in the statuses table that are connected with
	 * a user generated calendar on a specific month and year.
	 *
	 * @param	$month		int		The month of the posts
	 * @param	$year		int		The year of the posts
	 * @param	$id			int		The calendar id
	 * @return	$feed		Array	The statuses
	 */
	public function getUserCalendarFeed ($month, $year, $id)
	{
		$sql = $this->sqlStart . "WHERE month = ? AND year = ? AND calendar_id = ? ORDER BY statuses.id DESC";

		$feed = self::executeSql($sql, array($month, $year, $id));
		return $feed;
	} //end getUserCalendarFeed
	
	/**
	 * getUserCalendarLatest
	 *
	 * Retrieves the last 20 statuseses in the statuses table that are connected 
	 * with a user generated calendar.
	 *
	 * @param	$id		int		The calendar id
	 * @param	$limit	int		The number of rows to fetch from the database
	 * @return 	$feed	Array	The information from the database
	 */
	public function statuses_CalendarLatest ($id, $start = 0, $limit = 20)
	{							
		$sql = $this->sqlStart . "WHERE calendar_id = ? ORDER BY statuses.id DESC LIMIT " . $start . "," . $limit;

		$feed = self::executeSql($sql, array($id));
		return $feed;
	} //end getUserCalendarLatest
	
	public function statuses_CalendarRandom ($id, $start = 0, $limit = 20)
	{
		$sql = $this->sqlStart . "WHERE calendar_id = ? ORDER BY RAND() LIMIT " . $start . "," . $limit;
		
		$feed = self::executeSql($sql, array($id));
		return $feed;
	} //end statuses_CalendarRandom
	
	public function statuses_CalendarFeatured ($id, $start = 0, $limit = 20)
	{
		$sql = "SELECT statuses.*, featured_statuses.status_id, users.first_name, users.last_name, users.username, users.thumbnail FROM featured_statuses INNER JOIN users ON featured_statuses.user_id = users.id INNER JOIN statuses ON featured_statuses.status_id = statuses.id WHERE statuses.calendar_id = ? ORDER BY statuses.year DESC, statuses.month DESC, statuses.day DESC LIMIT 25";
		
		$feed = self::executeSql($sql, array($id));
		return $feed;
	} //end statuses_CalendarRandom
	
	/**
	 * getPersonalFeed
	 *
	 * Retrieves the posts of a specified user and none else for
	 * the current month.
	 *
	 * @param	$userId		int		The user id to check for friends
	 * @return	$feed		Array	The array of data from the database
	 */
	public function getPersonalFeed ($userId)
	{						
		$sql = $this->sqlStart . "WHERE statuses.user_id = ? ORDER BY statuses.id DESC";
		
		$feed = array(); 														//Initialize an empty array 
		$feed = self::executeSql($sql, array($userId, date('m'), date('Y')));	//Fetch the information from the database
		return $feed;															//Return the information
	} //end getPersonalFeed
	
	/**
	 * getUpcoming
	 *
	 * Retrives the upcomming posts for the public calendar
	 *
	 * @param	$limit		int		The number of rows to return from the database
	 * @return	$feed		Array	The info retrived from the DB
	 */
	public function statuses_PublicUpcoming ($limit = 20)
	{	
		$sql = $this->sqlStart . "INNER JOIN following ON statuses.user_id = following.user WHERE month >= ? AND day >= ? AND year >= ? ORDER BY statuses.year ASC, statuses.month ASC, statuses.day ASC LIMIT " . $limit;
		
		$feed = array();														//Initialize an empty array to avoid error in foreach
		$feed = self::executeSql($sql, array(date('m'), date('d'), date('Y')));	//Fetch the information from the database
		return $feed;
	} //end getUpcoming
	
	/**
	 * getLatest
	 *
	 * Retrives the posts made by people the user follows and orders it 
	 * it by the latest post made
	 *
	 * @param	$userId		int		The logged in user
	 * @param	$limit		int		The number of rows to retrieve from the database
	 * @return	$feed		Array	The info retrived from the DB
	 */
	public function statuses_FollowingLatest($userId, $limit = 100)
	{
		$sql = $this->sqlStart . "INNER JOIN following ON statuses.user_id = following.user WHERE following.follower = ? ORDER BY statuses.id DESC LIMIT " . $limit;
		
		$feed 		= array();	//Initialize an empty array to avoid error in foreach
		$feed 		= self::executeSQL($sql, array($userId));	//Fetch the information from the database
		return $feed;
	} //end getLatest
	
	/**
	 * getLatestCalendars
	 *
	 * Gets the latest statuses made from the all the calendars that a 
	 * specific user is following. 
	 *
	 * @param	$userId		int		The user who is following the calendars
	 * @return	$feed		Array	The information from the database
	 */
	public function statuses_AllCalendarsLatest ($userId, $limit = 100)
	{
		$sql = $this->sqlStart . "INNER JOIN following_calendar ON statuses.calendar_id = following_calendar.calendar_id WHERE following_calendar.user_id = ? ORDER BY statuses.id DESC LIMIT " . $limit;
		
		$feed = array();
		$feed = self::executeSql($sql, array($userId));
		return $feed;
	} //end getLatestCalendars
											
	/**
	 * getNearby
	 *
	 * Retrieves a list of all of the events that are happening in the
	 * exact same location as the user
	 */
	public function statuses_NearbyLatest ($userId)
	{
		$sql = $this->sqlStart . "WHERE users.id = ? AND users.location = statuses.location ORDER BY statuses.id DESC LIMIT 20";
		
		$feed = array();	//Initialize an empty array
		$feed = self::executeSql($sql, array($userId));
		
		return $feed;
	} //end getNearby
	
	/**
	 * getPopularLocations
	 *
	 * Retrieves the top 5 locations that most users are 
	 * located at at the moment.
	 *
	 * @return $row		array	The location
	 */
	public function getPopularLocations ()
	{
		$row = array();
		
		$Statement = $this->Database->prepare("SELECT location, count(location) AS 'number' FROM users WHERE location != '' GROUP BY location ORDER BY count(location) DESC LIMIT 5");
		$Statement->execute();
		$row = $Statement->fetchAll();
		
		return $row;
	} //end getPopularLocations
	
	/**
	 * getPopularDates
	 *
	 * Retrieves the top 7 dates that have the most posts 
	 * connected with them.
	 *
	 * @return $row		array	The month, day, and year
	 */
	public function getPopularDates ($calendarId = null)
	{
		$row = array();
		
		if ($calendarId == null)
		{
			$Statement = $this->Database->prepare("SELECT month, day, year, count(month) FROM statuses GROUP BY month, day, year ORDER BY count(month) DESC LIMIT 7");
			$Statement->execute();
		}
		else
		{
			$Statement = $this->Database->prepare("SELECT month, day, year, count(month) FROM statuses WHERE calendar_id = ? GROUP BY month, day, year ORDER BY count(month) DESC LIMIT 7");
			$Statement->execute(array($calendarId));
		}
		
		$row = $Statement->fetchAll();
		
		return $row;
	} //end getPopularDates
	
	/**
	 * getNotifications 
	 *
	 * Gets the latest notifications that a user has
	 *
	 * @param	$username		String	The username to get notifications for
	 * @return	$notifications	Array	The array of notifications
	 */
	public function getNotifications ($username)
	{
		$notifications = array();
		$username = addslashes($username);
		
		$sql = $this->sqlStart . "WHERE mentioned_users LIKE '%" . $username . "%' OR mentioned_users = ? ORDER BY statuses.id DESC LIMIT 20";
		$notifications = self::executeSql($sql, array($username));
		return $notifications;
	} //end getNotifications
	
	/**
	 * sortFeed
	 *
	 * Sorts a feed that returns values for the entire month, based on the day
	 * of the row in the array. The function also has an optional feature of 
	 * slicing the array into sub parts.
	 *
	 * @param	$array		array	The array to mess with
	 * @param	$day		int		The day of the sorted array
	 * @param	$start		int		The value to start the array at
	 * @param	$end		int		The value to end the array at
	 * @return	$sorted		array	The sorted array
	 */
	static public function sortFeed ($array, $day, $start = 0, $length = null)
	{
		$sorted = array();	//Initialize array
		
		//Generate the values we need
		foreach ($array as $row)
		{
			if ($row["day"] == $day) $sorted[] = $row;
		}

		//Cut out a specific section
		$sliced = array_slice($sorted, $start, $length);

		return $sliced;	//Return array to user
	} //end sortFeed

	static public function sortDay ($array, $day)
	{
		$sorted = array();	//Initialize array
		
		//Generate the values we need
		foreach ($array as $row)
		{
			if ($row["day"] == $day) $sorted = $row;
		}
		
		return $sorted;	//Return array to user
	} //end sortFeed
	
	
	/**
	 * formatTime
	 *
	 * Formats the time of a post
	 *
	 * @param	$hour	int		The hour of the post (24 hour)
	 * @param	$minute	int		The minte the post was made
	 * @return	$time	int		The correctly formated time
	 */
	public function formatTime ($hour, $minute)
	{
		$end 	= ($hour > 11) ? 'PM' : 'AM';
		$hour 	= ($hour > 12) ? $hour - 12 : $hour;
		$hour 	= (strlen($hour) == 1) ? '0' . $hour : $hour;
		$minute = (strlen($minute) == 1) ? '0' . $minute : $minute;
		
		$time 	= $hour . ':' . $minute . ' ' . $end;
		
		return $time;
	} //end formatTime
	
	/**
	 * executeSql
	 *
	 * Executes the sql statement for getting information from
	 * the database and returns it as an array
	 *
	 * @param	$sql		String	The prepared sql statement
	 * @param	$array		Array	The values to use with the sql statement
	 * @return	$fetched	Array	The information from the database
	 */
	protected function executeSql ($sql, $array)
	{
		$fetched = array();
		$Statement = $this->Database->prepare($sql);
		$Statement->execute($array);
		$fetched = $Statement->fetchAll();
		
		return $fetched;
	} //end executeSql
} //end Feed
