<?php

class Register 
{
	/**
	 * The database object
	 */
	private $Database;
	
	/**
	 * The username of the user (optional)
	 */
	private $username;
	
	/** 
	 * The first name of the user
	 */
	private $firstName;
	
	/**
	 * The last name of the user
	 */
	private $lastName;
	
	/**
	 * The thumbnail link of the user
	 */
	private $thumbnail;
	
	/**
	 * The gender of the user
	 */
	private $gender;
	
	/**
	 * The email of the user
	 */
	private $email;
	
	/**
	 * The location of the user
	 */
	private $location;
	
	/**
	 * The type of registration being made
	 */
	private $type;
	
	/**
	 * The user id of the user if they are registering through
	 * an API
	 */
	private $userId;
	
	/**
	 * The hashed password for a user registering through the 
	 * site
	 */
	private $password;
	
	public function __construct(&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	/**
	 * setFirstName
	 *
	 * The first name of the user being registered
	 */
	public function setFirstName ($firstName)
	{
		//Check for errors
		if (strlen($firstName) == 0)
			throw new Exception('A first name is required');
			
		$this->firstName = $firstName;
	} //end setFirstName
	
	/**
	 * setLastName
	 *
	 * The last name of the user being registered
	 */
	public function setLastName ($lastName)
	{
		//Check for errors
		if (strlen($lastName) == 0)
			throw new Exception('A last name is required');
			
		$this->lastName = $lastName;
	} //end setLastName
	
	/**
	 * setUsername
	 *
	 * The username of the user being registered
	 */
	public function setUsername ($username)
	{
		$Statement = $this->Database->prepare("SELECT id FROM users WHERE username = ?");
		$Statement->execute(array($username));
		$rowCount = $Statement->rowCount();
		
		if ($rowCount != 0)
			throw new Exception ('That username already exists. Please choose a different one.');
		else
			$this->username = $username;
	} //end setUsername
	
	/**
	 * setThumbnail
	 * 
	 * The link to the users thumbnail
	 */
	public function setThumbnail ($thumbnail)
	{
		$this->thumbnail = $thumbnail;
	} //end setThumbnail
	
	/**
	 * setGender
	 *
	 * The user's gender
	 */
	public function setGender ($gender)
	{
		$this->gender = $gender;
	} //end setGender
	
	/**
	 * setEmail
	 *
	 * The user's email. The email is required
	 */
	public function setEmail ($email)
	{
		if (empty($email))
			throw new Exception('There was an error with the registration. Please retry.');
		else
			$this->email = $email;
	} //end setEmail
	
	/**
	 * setLocation
	 *
	 * The location of the user
	 */
	public function setLocation ($location)
	{
		$this->location = $location;
	} //end setLocation
	
	/**
	 * setRegisterType
	 *
	 * The type of registration that is going on
	 * Options:
	 * - facebook 	(Facebooks API)
	 * - twitter	(Twitters API)
	 * - datehitter	(Through the site)
	 */
	public function setRegisterType ($type)
	{
		$this->type = $type;
	} //end setRegisterType
	
	/**
	 * setUserId
	 *
	 * The identifiable user id of the user. If they are registering
	 * through facebook, twitter, etc. this is what will be used to 
	 * identify them
	 */
	public function setUserId ($userId)
	{
		$this->userId = $userId;	
	} //end setUserId
	
	/**
	 * setPassword
	 *
	 * The password that the user wishes to create
	 *
	 * @param $password		String	The password
	 */
	public function setPassword ($password, $salt)
	{
		$password = $password . $salt;
		$password = hash('sha512', $password);
		
		$this->password = substr($password, 0, 64);
	} //end setPassword
	
	/**
	 * setUniqueUsername
	 *
	 * Checks to see if the username the user is requesting exists 
	 * already. If it already exists the function adds a 1 to the username, 
	 * and so on until it is unique.
	 *
	 * @param	$username		String	The username requested
	 * @return 	bool
	 */
	public function setUniqueUsername ($username)
	{
		$rowCount = 1;	//Set sentinal value
		
		//Sentinal Loop
		while ($rowCount != 0)
		{
			$Statement = $this->Database->prepare("SELECT id FROM users WHERE username = ?");
			$Statement->execute(array($username));
			$rowCount = $Statement->rowCount();
		
			if ($rowCount != 0)
				$username = $username . '1';
			
			continue;
		}
		
		$this->username = $username;
	} //end checkUsername
	
	/**
	 * Inserts the user's information into the database
	 */
	public function insertUser ()
	{
		if (self::userExists() == true)
			throw new Exception('We\'re sorry but that email is already registered.');
			
		$query = self::prepareInsertQuery();
		$array = self::prepareInsertArray();
		
		try
		{
			$Statement = $this->Database->prepare($query);
			$Statement->execute($array);
		}
		catch (PDOException $e)
		{
			throw new Exception('There was an error with the registration. Please re-try');
		}
	} //end insertUser
	
	/**
	 * userExists
	 *
	 * Checks to see if a user's email already exists in the database
	 *
	 * @return	bool		True if user exists, otherwise false
	 */
	private function userExists ()
	{
		$Statement = $this->Database->prepare("SELECT id FROM users WHERE email = ?");
		$Statement->execute(array($this->email));
		$rowCount = $Statement->rowCount();
		
		return ($rowCount == 0) ? false : true;
	} //end userExists
	
	/**
	 * prepareInsertArray 
	 *
	 * Prepares the array that is used to register the user
	 * 
	 * @return $array	Array	
	 */
	public function prepareInsertArray ()
	{
		$array[] = $this->email;		//Add the email
		
		if (!empty($this->username))	//If a username was given
			$array[] = $this->username;
		if (!empty($this->firstName))	//If a first name was given
			$array[] = $this->firstName;
		if (!empty($this->lastName))	//if a last name was given
			$array[] = $this->lastName;
		if (!empty($this->thumbnail))	//If a thumbnail was given
			$array[] = $this->thumbnail;
		if (!empty($this->gender))		//If a gender was given
			$array[] = $this->gender;
		if (!empty($this->location))	//If a location was given
			$array[] = $this->location;
		if (!empty($this->userId))		//If a third party user id was given
			$array[] = $this->userId;
		if (!empty($this->password))	//If regitering through datehitter, a password is required
			$array[] = $this->password;
			
		return $array;
	} //end prepareInsertArray
	
	/**
	 * prepareInsertQuery
	 *
	 * Prepares the mysql query that will be used for registering the user
	 * 
	 * @return $query	String	The query
	 */
	public function prepareInsertQuery ()
	{
		$values = 0;							//The number of columns being updated
		$query = "INSERT INTO users (email";	//The beginning of the query. Email is required
		
		if (!empty($this->username))		//If inserting the username
		{
			$query .= ",username";
			$values++;
		}
		if (!empty($this->firstName))		//If inserting the first name
		{
			$query .= ",first_name";
			$values++;
		}
		if (!empty($this->lastName))		//if inserting the last name
		{
			$query .= ",last_name";
			$values++;
		}
		if (!empty($this->thumbnail))		//If inserting the thumbnail
		{
			$query .= ",thumbnail";
			$values++;
		}
		if (!empty($this->gender))			//If inserting the gender
		{
			$query .= ",gender";
			$values++;
		}
		if (!empty($this->location))		//If inserting the location
		{
			$query .= ",location";
			$values++;
		}
		
		//Determine the type of user id to insert
		switch ($this->type)
		{
			case 'facebook':
				$query .= ",fb_id";
				$values++;
				break;
			case 'datehitter':
				$query .= ",password";
				$values++;
				break;
		}
		
		$query .= ") VALUES (?";	//More query preperation
		
		//Add the query parameters
		for ($count = 0; $count < $values; $count++)
		{
			$query .= ",?";
		}
		
		$query .= ")";
		
		return $query;
	} //end prepareInsertQuery
} //end Register

?>
