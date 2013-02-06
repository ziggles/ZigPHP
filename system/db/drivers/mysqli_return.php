<?php

/**
* MySQLi Return Class
*/
class mysqli_return
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
		return $this->result->num_rows;
	}
	
	public function seek($row_num)
	{
		if (!is_int($row_num)) 
		{
			showError('Row number must be an integer.', 'seek');
			return FALSE;
		}
		
		return ($this->result->data_seek($row_num)) ? TRUE : FALSE;
	}
	
	public function row()
	{
		return $this->result->fetch_row();
	}
	
	public function rows($return_as_array = 0)
	{
		if ($return_as_array == 1) 
		{
			$array = array();
			while ($row = $this->result->fetch_assoc()) 
			{
				$array[] = $row;
			}
			return $array;
		}
		return $this->result->fetch_assoc();
	}
	
	public function rows_as_object($return_as_object = 0)
	{
		if ($return_as_object == 1) 
		{
			$obj = new stdClass();
			
			$counter = 0;
			while ($row = $this->result->fetch_object()) 
			{
				$obj->$counter = $row;
				$counter++;
			}
			return $obj;
		}
		return $this->result->fetch_object();
	}
	
}

// END of ZigPHP MySQLi Return Driver
// Filename: mysqli_return.php 
// Location: root/system/db/drivers/