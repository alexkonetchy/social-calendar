<?php

class Friends
{
	/**
	 * The object for the database connection
	 */
	private $Database;
	
	/**
	 * The logged in user
	 */
	private $userId;
	
	public function __construct ($userId, &$Database)
	{
		$this->Database = $Database;	//The PDO database connection
		$this->userId = $userId;		//The user id to get the information from
	}
	
	public function getFollowers ()
	{
		$followers = array();
		
		$Statement = $this->Database->prepare("SELECT following.*, users.first_name, users.last_name, users.thumbnail FROM following INNER JOIN users ON following.follower = users.id WHERE following.user = ? ORDER BY following.id DESC");
		$Statement->execute(array($this->userId));
		$followers = $Statement->fetchAll();
		
		return $followers;
	} //end getFollowers
	
	public function getFollowing ()
	{
		$following = array();
		
		$Statement = $this->Database->prepare("SELECT following.*, users.first_name, users.last_name, users.thumbnail FROM following INNER JOIN users ON following.user = users.id WHERE following.follower = ? ORDER BY following.id DESC");
		$Statement->execute(array($this->userId));
		$following = $Statement->fetchAll();
		
		return $following;
	} //end getFollowing
} //end Friends

?>
