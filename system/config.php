<?php

/*
| ======================================================================
| ZigPHP Config Class
| ======================================================================
|
| This class is in charge of managing the config files. 
|
*/

Class Config 
{

	public static $values = array();

	public static function set($name, $value)
	{
		self::$values[$name] = $value;
	}

	public static function get($name)
	{
		if (!array_key_exists($name, self::$values))
		{
			showError('Config value - <b>'.$name.'</b> - does not exist', 'get');
		}
		return self::$values[$name];
	}

	public static function showConfig()
	{
		showVars(self::$values);
	}
		
}

// END of ZigPHP Config Class
// Filename: config.php 
// Location: root/system/