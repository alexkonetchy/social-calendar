<?php	
	//Process the description form 
	if (isset($_POST["send_reply" . $messageCount]))
	{	
		if (!isset($Message))
			$Message = new Message($user["id"], $Database);	//Instantiate the message class
			
		try
		{
			$Message->setReciever($_POST["sid" . $messageCount]);
			$Message->setText($_POST["message_text" . $messageCount]);
			$Message->send();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
		
		header("Location: " . $_SERVER["REQUEST_URI"]);	
	}
?>
