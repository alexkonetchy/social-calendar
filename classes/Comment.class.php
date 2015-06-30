<?php

class Comment
{
	private $Database;
	
	protected $sqlStart = "SELECT comments.*, users.first_name, users.last_name, users.thumbnail, users.username FROM comments INNER JOIN users ON comments.user_id = users.id ";
	
	public function __construct (&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	public function getComments ($postId)
	{
		$sql = $this->sqlStart . "WHERE post_id = ? ORDER BY id DESC";
		
		$Statement = $this->Database->prepare($sql);
		$Statement->execute(array($postId));
		$comments = $Statement->fetchAll();
		
		return $comments;
	} //end getComments
} //end Comment

?>
