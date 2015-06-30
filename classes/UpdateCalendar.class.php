<?php

require_once 'CreateCalendar.class.php';

class UpdateCalendar extends CreateCalendar
{
	/**
	 * The PDO object for the database
	 */
	private $Database;
	
	/**
	 * The id of the calendar being handled
	 */
	private $calendarId;
	
	public function __construct ($calendarId, &$Database)
	{
		$this->calendarId = $calendarId;
		$this->Database = $Database;
	} //end __construct
	
	/**
	 * editCalendar
	 *
	 * Updates the row in the database containing the calendar's information
	 * with the user defined data
	 */
	public function editCalendar ()
	{
		$Statement = $this->Database->prepare("UPDATE calendars SET title = ?, description = ? WHERE id = ?");
		$Statement->execute(array($this->title, $this->description, $this->calendarId));
	} //end editCalendar
	
	/**
	 * makePrimary
	 *
	 * Edits the users table to set the calendar as the users primary 
	 * calendar for viewing
	 *
	 * @param	$userId		int		The id of the user
	 */
	public function makePrimary ($userId)
	{
		$Statement = $this->Database->prepare("UPDATE users SET primary_calendar = ? WHERE id = ?");
		$Statement->execute(array($this->calendarId, $userId));
	}
	
	/**
	 * deleteCalendar
	 *
	 * Deletes the calendar from the 'calendars' table. Deletes all
	 * statuses posted to the calendar in the 'statuses' table. Subtracts
	 * one from the calendar count of a user
	 *
	 * @param	$userId		int		The id of the user
	 */
	public function deleteCalendar ($userId)
	{
		try
		{
			//Delete the calendar from the calendars table
			$Statement = $this->Database->prepare("DELETE FROM calendars WHERE id = ? AND user_id = ?");
			$Statement->execute(array($this->calendarId, $userId));
			
			//Delete all the statuses associated with the calendar
			$Statement = $this->Database->prepare("DELETE FROM statuses WHERE calendar_id = ? AND user_id = ?");
			$Statement->execute(array($this->calendarId, $userId));
			
			//Update the user's calendar count
			$Statement = $this->Database->prepare("UPDATE users SET num_calendars = num_calendars - 1 WHERE id = ?");
			$Statement->execute(array($userId));
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
		}
	} //end deleteCalendar
} //end UpdateCalendar

?>
