<?php

/*
| ======================================================================
| Zig CLI Bootstrap
| ======================================================================
|
*/

define('CLI_PATH', SYS_PATH . 'zig_cli/');

/*
|--------------------------------------------------------------------------
| Load the config class and app config
|--------------------------------------------------------------------------
|
*/
require SYS_PATH . 'config' . EXT;
require APP_PATH . 'config/app.config' . EXT;

/*
|--------------------------------------------------------------------------
| Load the framework helper functions
|--------------------------------------------------------------------------
|
*/
require SYS_PATH . 'zigphp_helper' . EXT;

/*
|--------------------------------------------------------------------------
| Load the page in charge of setting up the environment and error reporting "stuff" 
|--------------------------------------------------------------------------
|
*/
require SYS_PATH . 'zigphp_env' . EXT;

restore_error_handler();

require_once  CLI_PATH . 'zig_cli' . EXT;

$zig = new zig($argv);

echo PHP_EOL;

// END of Zig CLI Bootstrap
// Filename: bootstrap.php 
// Location: root/system/zig_cli/