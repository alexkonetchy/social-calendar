<?php

class Calendar
{	
	protected 	$month;		//The month given
	protected 	$year;		//The year given
	protected 	$total;		//The total number of days in a month
	
	/**
	 * constructor
	 *
	 * The months provided must have a leading zero
	 *
	 * $month	String	The month we are dealing with
	 * $year	String	The year we are dealing with
	 */
	public function __construct ($month = null, $year = null)
	{		
		if ($month == null) $month = date('m');	//If no month was supplied then it's the current one
		if ($year == null) 	$year = date('Y');	//If no year was supploed then it's the current one
		
		if (strlen($month) == 1) $month = '0' . $month;	//If the month is single digit add a zero
		
		$this->month 	= $month;
		$this->year 	= $year;
		$this->total 	= date('t', mktime(0,0,0,$month,1,$year));	//Calculate the total number of days for the month
		
	} //end __construct
	
	/**
	 * getEmpty
	 *
	 * Calculates the first day of the month for what was provided in the class
	 *
	 * @return $day		String	The day of the week the month starts on
	 */
	public function getEmpty ()
	{
		$day = date('w', mktime(0,0,0,$this->month,1,$this->year));
		return $day;
	} //end getEmpty
	
	/**
	 * getTitle
	 *
	 * Returns the month of the year as text
	 *
	 * @return $month	String	The month
	 */
	static public function getTitle ($month, $year)
	{
		if ($year == null || $month == null)
		{
			$title = date('F Y', mktime(0,0,0,date('m'),1,date('Y')));
		}
		else
		{
			$title = date('F Y', mktime(0,0,0,$month,1,$year));
		}
		
		return $title;
	} //end getTitle
	
	/**
	 * getNext
	 *
	 * Calculates what the next month should be according to the page
	 * parameters given by the site. If none set then uses current
	 * month
	 *
	 * @param	$month	int		The integer value of the month
	 * @param	$year	int		The integer value of the year
	 * @return	$next	array	The integer value of the next month in key "month" 
	 *							and year in key "year"
	 */
	static public function getNext ($month, $year)
	{
		if ($month == null || $year == null)
		{
			$nextM = ($month == 12) ? 1 : date('m') + 1;
			$nextY = ($month == 12) ? date('Y') + 1 : date('Y');
		}
		else
		{
			$nextM = ($month == 12) ? 1 : $month + 1;
			$nextY = ($month == 12) ? $year + 1 : $year;
		} 
		$next = array("month" => $nextM, "year" => $nextY);
		
		return $next;
	} //end getNext
	
	/**
	 * getPrevious
	 *
	 * Calculates what the previous month should be according to the page
	 * parameters given by the site. If none set then uses current
	 * month
	 *
	 * @param	$month		int		The integer value of the month
	 * @param	$year		int		The integer value of the year
	 * @return	$previous	array	The integer value of the previous month is in the
	 *								key "month" and the year is in "year"
	 */
	static public function getPrevious ($month, $year)
	{
		if ($month == null || $year == null)
		{
			$previousM = ($month == 1) ? 12 : date('m') - 1;
			$previousY = ($month == 1) ? date('Y') - 1 : date('Y');
		}
		else
		{
			$previousM = ($month == 1) ? 12 : $month - 1;
			$previousY = ($month == 1) ? $year - 1 : $year;
		} 
		$previous = array("month" => $previousM, "year" => $previousY);
		
		return $previous;
	} //end getPrevious
	
	/**
	 * Returns the month given
	 */
	public function getMonth()
	{
		return htmlentities($this->month);
	} //end getMonth
	
	/**
	 * Returns the year given
	 */
	public function getYear ()
	{
		return htmlentities($this->year);
	} //end getYear
	
	/**
	 * Returns the total number of days
	 */
	public function getTotal()
	{
		return $this->total;
	} //end getTotal
	
} //end Calender

?>
