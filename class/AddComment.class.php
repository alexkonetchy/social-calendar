<?php

class AddComment 
{
	private $Database;
	protected $userId;
	protected $postId;
	protected $comment;
	
	public function __construct (&$Database)
	{
		$this->Database = $Database;
	} //end __construct
	
	public function setUser ($userId)
	{
		$this->userId = $userId;
	} //end setUser
	
	public function setPostId ($postId)
	{
		$this->postId = $postId; 
	} //end setPostId
	
	public function setComment ($comment)
	{
		$this->comment = $comment;
	} //end setComment
	
	public function add ()
	{
		$Statement = $this->Database->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
		$Statement->execute(array($this->postId, $this->userId, $this->comment));
		
		return true;
	} //end add
} //end AddComment

?>
