<?php

class Login 
{
	protected 	$id;		//The users id
	private 	$Database;	//The PDO object for the database connection
	
	public function __construct (&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	/**
	 * setId
	 *
	 * Sets the id of the user
	 *
	 * @param $id	int		The user's id
	 */
	public function setId ($id)
	{
		$this->id = $id;
	} //end setId
	
	/**
	 * firstFacebookLogin
	 *
	 * Checks to see whether this is a user's first time using the site
	 *
	 * @return 	bool	Boolean		True if first login, false if not
	 */
	public function firstFacebookLogin ()
	{
		//Prepare the correct
		$Statement = $this->Database->prepare("SELECT * FROM users WHERE fb_id = ?");
		$Statement->execute(array($this->id));
		$rowCount = $Statement->rowCount();
		
		return ($rowCount == 0) ? true : false;
	} //end firstLogin
	
	/**
	 * signIn
	 *
	 * Validates the user information submitted by the sing in form against
	 * the information in the database
	 * 
	 * @param	$email		String	The email being used to log in
	 * @param	$password	String	The password being used to log in
	 * @param	$salt		String	The standard salt used to hash the password
	 * @return	$key		String	The session key to authenticate the user in the database
	 */
	public function signIn($email, $password, $salt)
	{
		$password = self::passwordHash($password, $salt, 64);	//Hash the password to check against the database

		try
		{
			$Statement = $this->Database->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
			$Statement->execute(array($email, $password));	//Execute the query
			$rowCount = $Statement->rowCount();				//See if the query selected anything
		}
		catch (PDOException $e)
		{
			echo 'There was an error. Please re-try the form submission. Thank you';
		}
		
		if ($rowCount == 0)
		{
			throw new Exception('No user was found with the details you provided. Please try again');
		}
		else
		{
			//Generate a random session key
			$key = 0;
			for ($count = 0; $count < 32; $count++) $key .= rand(0, 99);
			$key = md5($key);
			
			//Insert the key into the database
			$Statement = $this->Database->prepare("UPDATE users SET session_key = ? WHERE email = ? AND password = ?");
			$Statement->execute(array($key, $email, $password));
		}
			
		return $key;	//Return the session key 
	} //end signIn
	
	/**
	 * validate
	 *
	 * Validates the the session key for the user in the database matches the 
	 * one given
	 *
	 * @param	$key	String	The session key
	 * @return	bool			True if it matches, false otherwise
	 */
	public function validate ($key)
	{
		$Statement = $this->Database->prepare("SELECT id FROM users WHERE session_key = ?");
		$Statement->execute(array($key));
		$rowCount = $Statement->rowCount();
		
		return ($rowCount == 0) ? false : true;
	} //end validate
	 
	
	/**
	 * passwordHash
	 *
	 * Hashes a password using the standard salt
	 *
	 * @param	$password	String	The password to process
	 * @param	$salt		String 	The salt to hash the password with
	 * @param	$length		String	The length of the password hash
	 * @return	$hashedPw	String	The hashed password
	 */
	private function passwordHash ($password, $salt, $length)
	{
		$password 	= $password . $salt;
		$password 	= hash('sha512', $password);
		$hashedPw 	= substr($password, 0, $length);
		
		return $hashedPw;
	} //end passwordHash
	
	/**
	 * invitedUsers
	 */
	public function invitedUsers ()
	{
		$emails = array();
		
		$Statement = $this->Database->prepare("SELECT email FROM users");
		$Statement->execute();
		$emails = $Statement->fetchAll();
		
		return $emails;
	} //end invitedUsers
	
	static public function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
	}
} //end Login

?>
