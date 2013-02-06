<?php

/**
* Database driver
*/
class db_driver
{
	protected $db_host;
	protected $db_user;
	protected $db_pass;
	protected $db_name;
	protected $db_table_prefix;
	protected $active_config = array();
	
	protected $connection;
	protected $link;
	protected $last_query;
	
	protected $zig;
	
	// This property will hold information to be sent to the Dev Report. 
	public $debug_info = array();
		
	public function __construct()
	{
		$this->zig =& get_instance();		
		$default_config = config::get('DB_DEFAULT_CONFIG');
		$this->setActiveConfig($default_config);
	}
	
	public function setActiveConfig($config)
	{
		if ($config == '' || !isset($config))
		{
			showError('Database config name not defined');
			return FALSE;
		}
		
		if ($this->connection == TRUE) 
		{
			$this->close();
			$needs_connection = TRUE;
		}
		
		$db_config = config::get('DB_CONFIG');
		$config = $db_config[$config];
		
		$this->db_host = $config['db_host'];
		$this->db_user = $config['db_user'];
		$this->db_pass = $config['db_pass'];
		$this->db_name = $config['db_name'];
		$this->active_config = $config;
		$this->active_config['db_table_prefix'] = '';
		
		if (array_key_exists('db_table_prefix', $config)) 
		{
			$this->db_table_prefix = $config['db_table_prefix'];
			$this->active_config['db_table_prefix'] = $config['db_table_prefix'];
		}
		else
		{
			$this->db_table_prefix = NULL;
			$this->active_config['db_table_prefix'] = NULL;		
		}
		
		if ($needs_connection) 
		{
			$this->connect(); 
		}
	}
	
	public function is_connected()
	{
		return ($this->connection) ? TRUE : FALSE;
	}
	
	public function last()
	{
		return $this->last_query;
	}
	
	public function get_insert_id()
	{
		return $this->_insert_id();
	}
	
	protected function addDebugInfo($sql, $time)
	{
		$this->debug_info[] = array(
			'query' => $sql,
			'time' => $time
		);
	}
	
	public function connect($return_if_false = 0)
	{
		if ($this->connection == TRUE) 
		{
			return TRUE;
		}
		
		if (array_key_exists('db_pconnect', $this->active_config)) 
		{
			if ($this->active_config['db_pconnect'] == TRUE) 
			{
				$this->link = $this->_db_pconnect();
				return TRUE;
			}
		}
		
		$this->link = $this->_db_connect($return_if_false);
		
		if ($this->connection == TRUE) 
		{
			$this->select_db($this->db_name);
		}
	}
	
	public function close()
	{
		$this->_db_close();
	}
	
	public function select_db($db)
	{
		$this->_select_db($db);
	}
	
	public function escape($data)
	{
		if (is_array($data)) 
		{
			$clean_data = array();
			foreach ($data as $key => $value) 
			{
				if ($key != '')
				{
					$clean_data[$key] = $this->escape($value);
				}
				if (!is_array($value))
				{
					$clean_string = $this->_escape($value);
					$clean_data[$key] = $clean_string;				
				}
			}  
			return $clean_data;
		}
		elseif (is_object($data))
		{
			$clean_data = new stdClass();
			foreach ($data as $key => $value) 
			{
				if (isset($key))
				{
					if ($key != '')
					{
						$clean_data->$key = $this->escape($value);
					}
					if (!is_object($value))
					{
						$clean_string = $this->_escape($value);
						$clean_data->$key = $clean_string;
					}	
				}
			}
			return $clean_data;
		}
		
		return $this->_escape($data);
	}
	
	public function query($sql, $return_boolean = 0)
	{
		$result_driver = $this->driver . '_return';
		if (!$result = $this->_execute($sql))
		{
			return ($return_boolean == 0) ? new $result_driver($result) : FALSE;
		}
		else
		{
			return ($return_boolean == 0) ? new $result_driver($result) : TRUE;
		}
	}
	
	public function select($table, $fields = array(), $options = '')
	{
		if (count($fields) == 0 || $fields == '')
		{
			$search_fields = '*';
		}
		else
		{
			$field_count = count($fields);
			$search_fields = '';
			
			$count = 1;
			foreach ($fields as $field) 
			{
				if ($count == $field_count) 
				{
					$search_fields .= '`' . $field . '`'; 
				}
				else
				{
					$search_fields .= '`' . $field . '`, '; 
				}
				$count++;
			}
		}
		
		$sql = 'SELECT ' . $search_fields . ' FROM ' . $this->db_table_prefix . $table .  ' ' . $options;
				
		return $this->query($sql);
	}
	
	public function insert($table, $inserts)
	{
		if (is_object($inserts) OR is_array($inserts))
		{
			
		}
		else
		{
			showError('Inserts parameter must be an array or object.', 'insert');
			return FALSE;
		}
		
		if (is_object($inserts))
		{
			$insert_new = array();
			foreach ($inserts as $key => $value)
			{
				$insert_new[$key] = $value;
			}
			$inserts = $insert_new;
		}

		$insert_count = count($inserts);
		$fields = '';
		$values = '';
				
		$count = 1; 
		foreach ($inserts as $key => $value) 
		{
			if ($count == $insert_count) 
			{
				$fields .= '`' . $key . '`';
				$values .= "'" . $this->escape($value) . "'";
			}
			else
			{
				$fields .= '`' . $key . '`, ';
				$values .= "'" . $this->escape($value) . "', ";
			}
			$count++;
		}
				
		$sql = 'INSERT INTO ' . $this->db_table_prefix . $table . ' (' . $fields . ') VALUES (' . $values .')';
		
		return ($this->query($sql, 1)) ? TRUE : FALSE;
	}
	
	public function quick_insert($table, $field, $value)
	{
		$insert = array(
			$field => $value
		);
		
		return ($this->insert($table, $insert)) ? TRUE : FALSE;
	}
	
	public function update($table, $updates, $where)
	{
		if (!is_array($updates)) 
		{
			showError('Updates parameter must be an array.', 'update');
			return FALSE;
		}
		
		$update_count = count($updates);
		$update_string = '';
				
		$count = 1; 
		foreach ($updates as $key => $value) 
		{
			if ($count == $update_count) 
			{
				$update_string .= $key . " = '" . $this->escape($value) . "'";
			}
			else
			{
				$update_string .= $key . " = '" . $this->escape($value) . "', ";
			}
			$count++;
		}
		
		$sql = "UPDATE " . $this->db_table_prefix . $table . ' SET ' . $update_string . ' WHERE ' . $where;
				
		return ($this->query($sql, 1)) ? TRUE : FALSE;
	}
	
	public function quick_update($table, $field, $value, $where)
	{
		$update = array(
			$field => $value
		);
		
		return ($this->update($table, $update, $where)) ? TRUE : FALSE;
	}
	
	public function delete($table, $where)
	{
		$sql = 'DELETE FROM ' . $this->db_table_prefix . $table . ' WHERE ' . $where;
		
		return ($this->query($sql, 1)) ? TRUE : FALSE;
	}
	
	public function delete_table($table)
	{
		$sql = 'TRUNCATE TABLE ' . $this->db_table_prefix . $table;
		
		return ($this->query($sql, 1)) ? TRUE : FALSE;
	}
}

// END of ZigPHP Database Driver
// Filename: db.driver.php 
// Location: root/system/db/