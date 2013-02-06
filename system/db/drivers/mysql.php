<?php

/**
* MySQL Driver Class
*/
class zig_mysql_driver extends db_driver
{
	protected $driver = 'mysql';
	
	protected function _db_connect($return_if_false = 0)
	{
		if ($this->db_user == '' AND $this->db_pass == '')
		{
			$this->connection == FALSE;
			return FALSE;
		}
		
		$link = @mysql_connect($this->db_host, $this->db_user, $this->db_pass);
		
		if (!$link) 
		{
			$this->connection = FALSE;
			if ($return_if_false == 1)
			{
				return FALSE;
			}
			showDBError('Could not connect to database @ host: ' . $this->db_host);
			log_debug('Could not connect to database @ host: ' . $this->db_host);
			return FALSE;
		}
		
		mysql_set_charset('utf8', $link);
					
		log_debug('Connected to database @ host: ' . $this->db_host);
		$this->connection = TRUE;
		return $link;
	}
	
	protected function _db_pconnect()
	{
		$link = @mysql_pconnect($this->db_host, $this->db_user, $this->db_pass);
		
		if (!$link) 
		{
			showDBError('Could not connect to database @ host: ' . $this->db_host);
			log_debug('Could not connect to database @ host: ' . $this->db_host);
			return FALSE;
		}
		
		log_debug('Connected to database @ host: ' . $this->db_host);
		$this->connection = TRUE;
		return $link;
	}
	
	protected function _db_close()
	{
		if (mysql_close($this->link))
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
		return mysql_insert_id($this->link);
	}
	
	protected function _select_db($db)
	{
		if (mysql_select_db($db, $this->link))
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
		return mysql_error($this->link);
	}
	
	protected function _escape($string)
	{
		if (is_array($string) OR is_object($string))
		{
			return FALSE;
		}
		
		return mysql_real_escape_string($string, $this->link);
	}
	
	protected function _execute($sql)
	{
		if ($this->connection == FALSE) 
		{
			return FALSE;
		}
				
		$this->zig->dt->markTime('db_query_start');
		$result = mysql_query($sql, $this->link); 
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

// END of ZigPHP MySQL Database Driver
// Filename: mysql.php 
// Location: root/system/db/drivers/