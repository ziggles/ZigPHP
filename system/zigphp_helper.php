<?php

/*
| ======================================================================
| ZigPHP Helpers
| ======================================================================
|
| Contains a set of functions to assist in executing framework tasks. 
|
*/

function log_msg($msg, $log_file = '')
{
	$file = ($log_file == '') ?  BASE_PATH . '/logs/zig_error_log.txt' :  BASE_PATH . '/logs/' . $log_file;
	
	$date = date('m-d-o H:s');
	$msg = '[' . $date . ']' . ' - ' . $msg;
	
	if (file_exists($file))
	{
		if (is_writable($file))
		{
			$file_handler = fopen($file, 'a');
			fwrite($file_handler, $msg);
			fclose($file_handler);
		}
		else
		{
			showError('The ZigPHP error log file is not writable. Check your permissions for root/logs/', 'log_error', 0);
		}
	}
	else
	{
		$file_handler = fopen($file, 'a');
		fwrite($file_handler, $msg);
		fclose($file_handler);
	}
}

function log_debug($msg)
{
	if (config::get('ENABLE_DEBUGGING') === TRUE)
	{
		$msg = 'Debug: ' . $msg . '
';
		log_msg($msg, 'zig_debug_log.txt');
	}
	else
	{
		return;
	}
}

function load_class($class_name = '', $instantiate = TRUE)
{
	if (empty($class_name))
	{
		showError('Class name cannot be empty.', 'load_class');
	}

	$file_path = SYS_PATH . $class_name . EXT;

	if (!file_exists($file_path))
	{
		log_debug($class_name . ' not found');
		return FALSE;
	}
	else
	{
		include $file_path;
	}

	if ($instantiate === FALSE)
	{
		log_debug($class_name . ' class loaded');
		return;
	}

	$obj = new $class_name;
	
	log_debug($class_name . ' class loaded');

	return $obj;
}

function array_to_object($array = array()) 
{
	if (!is_array($array)) 
	{
		return $array;
	}
	$object = new stdClass();

	if (is_array($array) && count($array) > 0) 
	{
		foreach ($array as $name => $value) 
		{
			$name = (trim($name));
			if ($name != '')
			{
				$object->$name = array_to_object($value);
			}
		}  
		return $object;
	}
	else 
	{
		return FALSE;
	}
}	

function genRandomString($length='', $case_sensitive=0) 
{
	if (empty($length))
	{
		showError('Missing parameter for genRandomString. No length was given, numeric values only.', 'genRandomString');
		return FALSE;
	}
	if ($case_sensitive == 0)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	}
	elseif ($case_sensitive == 1)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}

	$string;   

	for ($p = 0; $p < $length; $p++) 
	{
		$string .= $characters[mt_rand(0, strlen($characters))];
	}

	return $string;
}

function showVars($variable)
{
	echo '<pre>';
	print_r($variable);
	echo '</pre>';
}

// END of ZigPHP Helpers
// Filename: zigphp_helper.php 
// Location: root/system/