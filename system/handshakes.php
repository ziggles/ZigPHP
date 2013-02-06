<?php

/*
| ======================================================================
| ZigPHP Handshakes Class
| ======================================================================
|
| This class is in charge of executing handshakes. 
|
*/

Class handshakes 
{
	
	private $handshakes;
	
	public function __construct($handshakes)
	{
		$this->handshakes = $handshakes;
	}
	
	private function runHandshake($handshake, $type)
	{
		if (!is_array($handshake))
		{
			showError($type . ' handshake must be an array. Please re-check your handshakes.config.php file.');
			return FALSE;
		}
		
		if (!array_key_exists('file_path', $handshake)) 
		{
			showError($type . ' handshake error: No file_path defined. Please re-check your handshakes.config.php file.');
			return FALSE;
		}
		
		$class = $handshake['class'];
		$filepath = APP_PATH . $handshake['file_path'];
		$method = $handshake['method'];
		
		if (!file_exists($filepath)) 
		{
			showError($type . ' handshake error: file could not be found in <b>' . $filepath . '</b>. Please re-check your handshakes.config.php file.');
			return FALSE;
		}
		
		include($filepath);
			
		if ($class == '' OR $class == NULL) 
		{
			// If no method is defined, we assume that the user wants to run a script not a function or a method. 
			if ($method == '' OR $method == NULL) 
			{
				return;
			}
			
			call_user_func($method);
			return;
		}
		
		if (!class_exists($class)) 
		{
			showError($type . ' handshake error: <b>' . $class . '</b> class could not be found. Please re-check your handshakes.config.php');
			return FALSE;
		}
		
		$handler = new $class;
		
		call_user_func(array($handler, $method));
		return;
	}
	
	public function runPreSysExec()
	{
		$handshakes = $this->handshakes['pre_sys_exec'];
		if (count($handshakes) > 0) 
		{
			foreach ($handshakes as $handshake) 
			{
				$this->runHandshake($handshake, 'pre_sys_exec');
			}
		}
	}
	
	public function runPreController()
	{
		$handshakes = $this->handshakes['pre_controller'];
		if (count($handshakes) > 0) 
		{
			foreach ($handshakes as $handshake) 
			{
				$this->runHandshake($handshake, 'pre_controller');
			}
		}
	}
	
	public function runPostController()
	{
		$handshakes = $this->handshakes['post_controller'];
		if (count($handshakes) > 0) 
		{
			foreach ($handshakes as $handshake) 
			{
				$this->runHandshake($handshake, 'post_controller');
			}
		}
	}
	
	public function runPostSysExec()
	{
		$handshakes = $this->handshakes['post_sys_exec'];
		if (count($handshakes) > 0) 
		{
			foreach ($handshakes as $handshake) 
			{
				$this->runHandshake($handshake, 'post_sys_exec');
			}
		}
	}
	
}

// END of ZigPHP Handshakes Class
// Filename: handshakes.php 
// Location: root/system/