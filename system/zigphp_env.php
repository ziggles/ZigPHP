<?php

/*
| ======================================================================
| ZigPHP Enviroment  
| ======================================================================
|
| This page sets up a runtime enviroment for ZigPHP. 
|
*/

error_reporting(E_ALL ^ E_NOTICE);

if (config::get('APP_ENV') == 'DEV')
{
	ini_set('display_errors', 'On');
}
else 
{
	ini_set('display_errors', 'Off');
}

ini_set('register_globals', 'Off');
ini_set('magic_quotes_gpc', 'Off');
ini_set('ignore_repeated_errors', 'On');
ini_set('html_errors', 'Off');

// Set the custom error handler.
set_error_handler("zigErrorHanler");

// Our custom error handler function.
function zigErrorHanler($err_num, $str, $file, $line) 
{
	if ( 0 == error_reporting ()) 
	{
		return;
	}
	
	if ($err_num == 2048)
	{
		return;
	}
 	if ($err_num == 8)
	{
		return;
	}
	
	$zig =& get_instance();
	
	$err_msg = 'PHP Error: ' . $str;
	
	log_debug('PHP error encountered');
	
	$zig->runtime_errors[] = array(
		'err_msg' => $err_msg,
		'file' => $file,
		'line' => $line,
		'log_type' => 2
	);

	if (config::get('LOG_ERRORS') === TRUE)
	{
		$level = config::get('LOG_LEVEL');
		if ($level == 2 || $level == 3)
		{
			$msg = $err_num . ' - PHP Error: ' . $str . ' in ' . $file . ' at line ' . $line . '
';
			log_msg($msg);
		}
	}
} 

// Our error handler for user generated errors. This replaces trigger_error()
function showError($err_msg, $error_type='', $log_err = 1)
{
	$zig =& get_instance();
	
	$err_msg = 'Framework Error: ' . $err_msg;
	
	$trace = setTraceByFunction($error_type);
	
	log_debug('Framework error encountered');
	
	$zig->runtime_errors[] = array(
		'err_msg' => $err_msg,
		'file' => $trace['file'],
		'line' => $trace['line'],
		'log_type' => 1
	);
		
	if (config::get('LOG_ERRORS') === TRUE && $log_err == 1)
	{
		$level = config::get('LOG_LEVEL');
		if ($level == 1 || $level == 3)
		{
			if ($trace['file'] == '' AND $trace['line'] == '') 
			{
				$msg = $err_msg . '
';				
			}
			else
			{
				$msg = $err_msg . ' in ' . $trace['file'] . ' at line ' . $trace['line'] . '
';
			}
			log_msg($msg);
		}
	}
}

// Duplicate of showError but for database errors. 
function showDBError($err_msg, $error_type='', $log_err = 1)
{
	$zig =& get_instance();
		
	$err_msg = 'Database Error: ' . $err_msg;
	
	$err_msg .= '<br><br>Affected Query: ' . $zig->db->last();
		
	$trace = setTraceByFunction($error_type);
	
	$zig->runtime_errors[] = array(
		'err_msg' => $err_msg,
		'file' => $trace['file'],
		'line' => $trace['line'],
		'log_type' => 2
	);
				
	log_debug('Database error encountered');
		
	if (config::get('LOG_ERRORS') === TRUE && $log_err == 1)
	{
		$level = config::get('LOG_LEVEL');
		if ($level == 1 || $level == 3)
		{
			if ($trace['file'] == '' AND $trace['line'] == '') 
			{
				$msg = $err_msg . '
';				
			}
			else
			{
				$msg = $err_msg . ' in ' . $trace['file'] . ' at line ' . $trace['line'] . '
';
			}
			
			log_msg($msg);
		}
	}
}

function setTraceByFunction($function)
{
	$traces = debug_backtrace();
	foreach ($traces as $level) 
	{
		if ($level['function'] == $function)
		{
			$trace = array('file' => $level['file'], 'line' => $level['line']);
			return $trace;
		}
	}
}

// END of ZigPHP Enviroment
// Filename: zigphp_env.php 
// Location: root/system/