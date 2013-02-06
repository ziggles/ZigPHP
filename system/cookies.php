<?php

/*
| ======================================================================
| ZigPHP Cookie Class
| ======================================================================
|
| This class is in charge of setting and getting cookies. 
|
*/

class cookies 
{
	private $zig; 
	public $data;
	
	public function __construct()
	{
		$this->zig =& get_instance(); 
		$this->setCookieData();
	}
	
	public function __get($key)
	{
		return $this->data->$key;
	}
	
	private function setCookieData()
	{
		foreach ($_COOKIE as $key => $value)
		{
			$this->data->$key = $value;
		}
		log_debug('Cookie data set');
	}
	
	public function set($key, $value, $expire, $path = '', $domain = '')
	{
		$url = (parse_url($this->zig->base_url));
		
		if ($path == '') 
		{
			$path = $url['path'];
		}
		
		if ($domain == '') 
		{
			if ($url['host'] == 'localhost') 
			{
				$domain = '';
			}
			else
			{
				$domain = '.' . $url['host'];
			}
		}
		
		$this->data->$key = $value;
		setcookie($key, $value, time() + $expire, $path, $domain);
		log_debug($key . ' added to session data');
	}
	
	public function delete($key, $expire, $path = '', $domain = '')
	{
		$url = (parse_url($this->zig->base_url));
		
		if ($path == '') 
		{
			$path = $url['path'];
		}
		
		if ($domain == '') 
		{
			if ($url['host'] == 'localhost') 
			{
				$domain = '';
			}
			else
			{
				$domain = '.' . $url['host'];
			}
		}
		
		unset($this->data->$key);
		setcookie($key, '', time() - $expire, $path, $domain);
		log_debug($key . ' deleted from cookie data');
	}	
	
}

// END of ZigPHP Session Class
// Filename: session.php 
// Location: root/system/