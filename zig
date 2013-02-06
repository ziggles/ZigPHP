#!/usr/bin/php
<?php

/*
|--------------------------------------------------------------------------
| Zig CLI 
|--------------------------------------------------------------------------
|
| Access this script from the CLI by typing 'php zig'. 
|
*/
define ('ZIG_VERSION', '1.0.0');
define ('CLI_VERSION', '1.0');

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
| Load the Zig CLI Bootstrap Controller
|--------------------------------------------------------------------------
|
| Launching application in 3.. 2.. 1.. GO! 
|
*/
require_once SYS_PATH . '/zig_cli/bootstrap.php';

echo PHP_EOL;

// END of Zig CLI
// Filename: zig
// Location: root/
