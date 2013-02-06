<?php

define ('ZIG_VERSION', '1.0.0');
define ('CLI_VERSION', '1.0');

/*
|--------------------------------------------------------------------------
| Application Constants
|--------------------------------------------------------------------------
|
| These settings should only be changed if necessary. NO leading or trailing slash.
|
| 'APP_FOLDER' = Name of the application folder
| 'SYS_FOLDER' = Name of the system folder
| 'EXT' = Default file extension. Usually .php
|
*/

$APP_FOLDER = 'application';
$SYS_FOLDER = 'system';
$EXT = '.php';


// Define applicaiton constants
$BASE_PATH = realpath(dirname(__FILE__));

define ('APP_FOLDER', $APP_FOLDER);
define ('BASE_PATH', $BASE_PATH);
define ('APP_PATH', $BASE_PATH . '/' . $APP_FOLDER.'/');
define ('SYS_PATH', $BASE_PATH . '/' . $SYS_FOLDER.'/');
define ('EXT', $EXT);

/*
|--------------------------------------------------------------------------
| Load Front Controller
|--------------------------------------------------------------------------
|
| Launching application in 3.. 2.. 1.. GO! 
|
*/
require_once SYS_PATH . 'zigphp.php';

// END of ZigPHP index.php
// Filename: index.php 
// Location: root/