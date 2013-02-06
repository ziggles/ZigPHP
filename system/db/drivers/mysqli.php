<?php

/**
* MySQLi Driver Class
*/
class zig_mysqli_driver extends db_driver
{
	protected $driver = 'mysqli';
	
	protected function _db_connect($return_if_false = 0)
	{
		if ($this->db_user == '' AND $this->db_pass == '')
		{
			$this->connection == FALSE;
			return FALSE;
		}
		
		$mysqli = new mysqli($this->db_host, $this->db_user, $this->db_pass);
		
		if ($mysqli->connect_errno)
		{
			$this->connection = FALSE;
			if ($return_if_false == 1)
			{
				return FALSE;
			}
			showDBError('Could not connect to database @ host: ' . $this->db_host);
			showDBError($mysqli->connect_error);
			
			log_debug('Could not connect to database @ host: ' . $this->db_host);
			return FALSE;
		}
		
		$mysqli->set_charset("utf8");
							
		log_debug('Connected to database @ host: ' . $this->db_host);
		$this->connection = TRUE;
		return $mysqli;
	}
	
	protected function _db_pconnect()
	{
		$mysqli = new mysqli('p:' . $this->db_host, $this->db_user, $this->db_pass);
		
		if ($mysqli->connect_errno)
		{
			$this->connection = FALSE;
			if ($return_if_false == 1)
			{
				return FALSE;
			}
			showDBError('Could not connect to database @ host: ' . $this->db_host);
			showDBError($mysqli->connect_error);
			
			log_debug('Could not connect to database @ host: ' . $this->db_host);
			return FALSE;
		}
		
		$mysqli->set_charset("utf8");
		
		log_debug('Connected to database @ host: ' . $this->db_host);
		$this->connection = TRUE;
		return $mysqli;
	}
	
	protected function _db_close()
	{
		if ($this->link->close())
		{
			$this->connection = FALSE;
			$this->link = NULL;
			log_debug("MySQL database connection closed");
			return TRUE;
		}
		else
		{
			showDBError('Error closing database connection');
			return FALSE;
		}
	}
	
	protected function _insert_id()
	{
		return $this->link->insert_id;
	}
	
	protected function _select_db($db)
	{
		if ($this->link->select_db($db))
		{
			return TRUE;
		}
		else
		{
			showDBError('Could not select database: ' . $this->_show_error());
		}
	}
	
	protected function _show_error()
	{
		return $this->link->error;
	}
	
	protected function _escape($string)
	{
		if (is_array($string) OR is_object($string))
		{
			return FALSE;
		}
		
		return $this->link->real_escape_string($string);
	}
	
	protected function _execute($sql)
	{
		if ($this->connection == FALSE) 
		{
			return FALSE;
		}
				
		$this->zig->dt->markTime('db_query_start');
		$result = $this->link->query($sql); 
		$this->zig->dt->markTime('db_query_end');
		
		$this->last_query = $sql;
		
		if (!$result) 
		{
			showDBError($this->_show_error(), '_execute');
			return FALSE;
		}
		
		if (config::get('APP_ENV') == 'DEV')
		{
			$this->addDebugInfo($sql, $this->zig->dt->compareTimes('db_query_start', 'db_query_end'));
		}
		
		return $result;
	}
}

// END of ZigPHP MySQLi Database Driver
// Filename: mysqli.php 
// Location: root/system/db/drivers/