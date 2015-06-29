<?php
	
	if (isset($_POST["register"]))				//The registration form was submitted
	{
		$_SESSION["first_login"] = true;		//The user's first login
		$Register = new Register($Database);	//Instantiate the register class
		
		try
		{
			$Register->setRegisterType('datehitter');					//The user is registering through Datehitter
			$Register->setUsername($_POST["username"]);					//The user's username
			$Register->setFirstName($_POST["first_name"]);				//The user's first name
			$Register->setLastName($_POST["last_name"]);				//The user's last name
			$Register->setEmail($_POST["email"]);						//The user's email
			$Register->setPassword($_POST["password"], $salt);			//The user's password
			$Register->setThumbnail($url . '/images/default_pp.png');	//Save the user's thumbnail
			
			$Register->insertUser();									//Register the user
		}
		catch (Exception $e)
		{
			echo $e->getMessage();										//Echo any errors
		}	
	}

	
?>
