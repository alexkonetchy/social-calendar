<?php

class Message 
{
	private $sender_id;
	private $reciever;
	private $text;
	private $Database;
	
	public function __construct ($sender_id, &$Database)
	{
		$this->Database 	= $Database;
		$this->sender_id 	= $sender_id;
	} //end constructor
	
	public function setReciever ($reciever)
	{
		$this->reciever = $reciever;
	} //end setReciever
	
	public function setText ($text)
	{
		$this->text = $text;
	} //end setText
	
	public function send ()
	{
		$Statement = $this->Database->prepare("INSERT INTO messages (sender_id, reciever, text) VALUES (?, ?, ?)");
		$Statement->execute(array($this->sender_id, $this->reciever, $this->text));
	} //end send
} //end Message

?>	
