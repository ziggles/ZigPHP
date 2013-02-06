<?php

/*
| ======================================================================
| ZigPHP Database Front Controller
| ======================================================================
|
| Front controller for interacting with database drivers and application. 
|
*/

define('DB_PATH', SYS_PATH . 'db/');
require_once DB_PATH . 'db.driver' . EXT;

function loadDriver($driver)
{
	if (empty($driver))
	{
		showError('DB Driver not defined.', 'loadDriver');
		return FALSE;
	}
	
	$file_path = DB_PATH . 'drivers/' . $driver . EXT;	

	if (!file_exists($file_path))
	{
		showError($driver . ' DB driver not found', 'loadDriver');
		log_debug($driver . ' DB driver not found.');
		return FALSE;
	}
	else
	{
		require_once $file_path;
		return TRUE;
	}
}

function &db_instance($return_if_false = 0)
{
	$config_driver = strtolower(config::get('DB_DRIVER'));
	$config_driver = ($config_driver == '' || empty($config_driver)) ? 'mysql' : $config_driver;
	
	if (loadDriver($config_driver) && loadDriver($config_driver . '_return')) 
	{
		$driver = 'zig_' . $config_driver . '_driver';
		$db = new $driver();
	} 
	else
	{
		return FALSE;
	}
	
	$db->connect($return_if_false);
	
	return $db;
}

// END of ZigPHP Database Front Controller
// Filename: database.php 
// Location: root/system/db/