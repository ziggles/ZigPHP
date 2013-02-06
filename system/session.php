<?php

/*
| ======================================================================
| ZigPHP Session Class
| ======================================================================
|
| This class makes handling session variables a lot easier by 
| streamlining the usage in the controller and models. 
|
*/

class session 
{
	private $zig; 
	public $data;
	
	public function __construct()
	{
		$this->zig =& get_instance(); 
		$this->createBaseData();
	}
	
	public function __get($key)
	{
		return $this->data->$key;
	}
	
	private function createBaseData()
	{
		foreach ($_SESSION as $key => $value)
		{
			$this->data->$key = $value;
		}
		
		if (empty($this->data->last_update))
		{
			$this->set('last_update', time());
		}
		
		if (empty($this->data->csrf_token))
		{
			$token = genRandomString(30);
			$this->set('csrf_token', $token);
		}

		$this->set('IP', $_SERVER['REMOTE_ADDR']);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->set('user_agent', $user_agent);
		
		log_debug('Basic session data updated');
	}
	
	public function set($key, $value)
	{
		$_SESSION[$key] = $value; 
		$this->data->$key = $value;
		log_debug($key . ' added to session data');
	}
	
	public function delete($key='')
	{
		if (empty($key))
		{
			unset($this->data);
		    session_unset();
		    session_destroy();
			log_debug('Session data deleted');
		}
		else
		{
			unset($this->data->$key);
			unset($_SESSION[$key]);
			log_debug($key . ' deleted from session data');
		}
	}	
	
}

// END of ZigPHP Session Class
// Filename: session.php 
// Location: root/system/