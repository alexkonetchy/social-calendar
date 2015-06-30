<?php

class Search
{
	protected $include;
	protected $tags;
	protected $location;
	protected $date;
	protected $user;
	private   $Database;
	
	public function __construct (&$Database)
	{
		$this->Database = $Database;
	}
	
	public function setInclude ($include)
	{
		$this->include = $include;
	} //end setInclude
	
	public function setTags ($tags)
	{
		$this->tags = $tags;
	} //end setTags
	
	public function setLocation ($location)
	{
		$this->location = $location;
	} //end setLocation
	
	public function setDate ($date)
	{
		if (!empty($date))
		{
			//Seperate the date into arrays for month, day, year
			$date = explode('-', $date);
			
			//Check to make sure the array is numeric
			if (!is_numeric($date[0]) || !is_numeric($date[1]) || !is_numeric($date[2]))
			{
				throw new Exception('The date must be numeric. Please enter it in the "mm-dd-yyyy" format');
			}
			else if (strlen($date[0]) != 2 || strlen($date[1]) != 2 || strlen($date[2]) != 4)
			{
				throw new Exception('Your date must be in the "mm-dd-yyyy" format');
			}
			else if ($date[0] > 12 || $date[0] < 0)
			{
				throw new Exception('Your month must be valid');
			}
			else if ($date[1] > 31 || $date[1] < 0)
			{
				throw new Exception('Your day must be valid');
			}
			else
			{
				$this->date = $date;
			}
		}
	} //end setDate
	
	public function setUser ($user)
	{
		$this->user = $user;
	} //end setUser
	
	private function queryUser ()
	{
		$row = array();
		$Statement = $this->Database->prepare("");
		$Statement->execute(array($this->user, $this->user));
		$row = $Statement->fetchAll();

		return $row;
	} //end queryUser
	
	private function queryDate ()
	{
		$row = array();
		$Statement = $this->Database->prepare("SELECT statuses.*, users.first_name, users.last_name, users.thumbnail FROM statuses INNER JOIN users ON statuses.user_id = users.id WHERE month = ? AND day = ? AND year = ? ORDER BY id DESC LIMIT 50");
		$Statement->execute(array($this->date[0], $this->date[1], $this->date[2]));
		$row = $Statement->fetchAll();
		
		return $row;
	} //end queryDate
	
	private function queryIncluded ()
	{
		$row = array();
		$Statement = $this->Database->prepare("SELECT statuses.*, users.first_name, users.last_name, users.thumbnail, MATCH(content) AGAINST (? IN BOOLEAN MODE) AS relevance FROM statuses INNER JOIN users ON statuses.user_id = users.id WHERE MATCH(content) AGAINST (? IN BOOLEAN MODE) HAVING relevance > 0 ORDER BY relevance DESC LIMIT 50");
		$Statement->execute(array($this->include, $this->include));
		$row = $Statement->fetchAll();
		
		return $row;
	} //end queryIncluded
	
	private function queryTags ()
	{
		$row = array();
		$Statement = $this->Database->prepare("SELECT statuses.*, users.first_name, users.last_name, users.thumbnail, MATCH(tags) AGAINST (? IN BOOLEAN MODE) AS relevance FROM statuses INNER JOIN users ON statuses.user_id = users.id WHERE MATCH(tags) AGAINST (? IN BOOLEAN MODE) HAVING relevance > 0 ORDER BY relevance DESC LIMIT 50");
		$Statement->execute(array($this->tags, $this->tags));
		$row = $Statement->fetchAll();
		
		return $row;
	} //end queryTags
	
	private function queryLocation ()
	{
		$row = array();
		$Statement = $this->Database->prepare("SELECT statuses.*, users.first_name, users.last_name, users.thumbnail, MATCH(statuses.location) AGAINST (? IN BOOLEAN MODE) AS relevance FROM statuses INNER JOIN users ON statuses.user_id = users.id WHERE MATCH(statuses.location) AGAINST (? IN BOOLEAN MODE) HAVING relevance > 0 ORDER BY relevance DESC LIMIT 50");
			$Statement->execute(array($this->location, $this->location));
		$row = $Statement->fetchAll();
		
		return $row;
	} //end queryLocation
	
	public function query ()
	{
		$included 	= array();
		$tags 		= array();
		$location	= array();
		$user		= array();
		$date 		= array();
		
		if (!empty($this->include))
		{
			$included = self::queryIncluded();
		}
		if (!empty($this->user))
		{
			$user = self::queryUser();
		}
		if (!empty($this->date))
		{
			$date = self::queryDate();
		}
		if (!empty($this->tags))
		{
			$tags = self::queryTags();
		}
		if (!empty($this->location))
		{
			$location = self::queryLocation();
		}

		$results = array_merge($included, $tags, $location, $user, $date);
		
		return $results;
		
	} //end query
} //end Search

?>
