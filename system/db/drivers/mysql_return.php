<?php

/**
* MySQL Return Class
*/
class mysql_return
{
	private $result;
	
	public function __construct($result)
	{
		$this->result = $result;
	}
	
	public function return_result()
	{
		return $this->result;
	}
	
	public function num_rows()
	{
		return mysql_num_rows($this->result);
	}
	
	public function seek($row_num)
	{
		if (!is_int($row_num)) 
		{
			showError('Row number must be an integer.', 'seek');
			return FALSE;
		}
		
		return (mysql_data_seek($this->result, $row_num)) ? TRUE : FALSE;
	}
	
	public function row()
	{
		return mysql_fetch_row($this->result);
	}
	
	public function rows($return_as_array = 0)
	{
		if ($return_as_array == 1) 
		{
			$array = array();
			while ($row = mysql_fetch_assoc($this->result)) 
			{
				$array[] = $row;
			}
			return $array;
		}
		return mysql_fetch_assoc($this->result);
	}
	
	public function rows_as_object($return_as_object = 0)
	{
		if ($return_as_object == 1) 
		{
			$obj = new stdClass();
			
			$counter = 0;
			while ($row = mysql_fetch_object($this->result)) 
			{
				$obj->$counter = $row;
				$counter++;
			}
			return $obj;
		}
		return mysql_fetch_object($this->result);
	}
	
}

// END of ZigPHP MySQL Return Driver
// Filename: mysql_return.php 
// Location: root/system/db/drivers/