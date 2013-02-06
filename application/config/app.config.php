<?php

/*
| ======================================================================
| Application Config
| ======================================================================
|
| All basic application settings are located in this file. 
|
*/

/*
|--------------------------------------------------------------------------
| Applicaiton Enviroment
|--------------------------------------------------------------------------
|
| If APP_ENV is set to DEV, ZigPHP will create a development environment with
| on page errors. If APP_ENV is set to PROD, ZigPHP will put the app in 
| production mode and not allow any on page errors. 
|
| If SHOW_DEV_REPORT is set to TRUE, ZigPHP will show a report of the execution 
| time, queries run, etc... at the bottom of the page. 
|
| If ENABLE_DEBUGGING is set to TRUE, ZigPHP will show debugging information 
| in a log file. Logs are stored in your/ZigPHP/root/logs/zig_debug_log.txt
|
*/
config::set("APP_ENV", 'DEV');
config::set("SHOW_DEV_REPORT", TRUE);
config::set("ENABLE_DEBUGGING", FALSE);

/*
|--------------------------------------------------------------------------
| Enable ZigPHP logs
|--------------------------------------------------------------------------
|
| If LOG_ERRORS is set to TRUE, ZigPHP will log PHP, MySQL, and Framework 
| errors. Logs are stored in your/ZigPHP/root/logs/zig_error_log.txt
|
| Log Levels
| 1 - Log only framework errors
| 2 - Log only PHP and MySQL errors
| 3 - Log all errors 
|
*/
config::set("LOG_ERRORS", FALSE);
config::set("LOG_LEVEL", 1);

/*
|--------------------------------------------------------------------------
| Site URL
|--------------------------------------------------------------------------
|
| URL to your zigPHP root. Typically this will be your base URL.
| Make sure to include http:// or https:// at the beginning of the URL.
| WITH a trailing slash
| 
|	ex. http://mysite.com/
|	ex. http://localhost/test/
|
*/
config::set('SITE_URL', 'http://localhost:80/zig/');

/*
|--------------------------------------------------------------------------
| Default Controller
|--------------------------------------------------------------------------
|
| If no specific controller is specified in the URI, ZigPHP will load the 
| default controller specified below. 
|
*/
config::set('DEFAULT_CONTROLLER', 'welcome');

/*
|--------------------------------------------------------------------------
| Skin Settings
|--------------------------------------------------------------------------
|
| All of your application's views are located in skin folders.
| Skins are located in your/ZigPHP/root/application/template.
| 'ACTIVE_SKIN' = The folder name of the active skin. 
|
*/
config::set('ACTIVE_SKIN', 'default');

/*
|--------------------------------------------------------------------------
| Enable Multiple Language Support
|--------------------------------------------------------------------------
|
| ZigPHP allows you to run your applicaiton in multiple languages. 
| Enabling multiple languages will add language identifiers in the URL
| 
|
*/
config::set('ENABLE_MULTI_LANG', TRUE);

/*
|--------------------------------------------------------------------------
| Enable Cookies
|--------------------------------------------------------------------------
|
| If set to true, ZigPHP will load the cookie class and make cookies 
| available in the registry. 
|
*/
config::set('ENABLE_COOKIES', TRUE);

/*
|--------------------------------------------------------------------------
| Auto-load Classes
|--------------------------------------------------------------------------
|
| ZigPHP allows you to auto-load libraries and helpers so you don't have to do 
| it manually in the controllers. To auto-load a class simply add the name of 
| the library/helper into the correct array. 
|
| Example: $AUTOLOAD_LIBRARIES = array('form', 'validation');
|
| Example: $AUTOLOAD_HELPERS = array('html');
|
*/
$AUTOLOAD_LIBRARIES = array();
$AUTOLOAD_HELPERS = array('html');

// DO NOT EDIT THE THREE LINES BELOW.
config::set('AUTOLOAD_LIBRARIES', $AUTOLOAD_LIBRARIES);
config::set('AUTOLOAD_HELPERS', $AUTOLOAD_HELPERS);

/*
|--------------------------------------------------------------------------
| Auto-load Models
|--------------------------------------------------------------------------
|
| If set to TRUE, ZigPHP will auto-load the model associated with the 
| controller. If the file doesn't exist, any include errors will be ignored.
|
| By convention, models should be named after the controller. 
|
*/
config::set('AUTOLOAD_MODEL', TRUE);

/*
|--------------------------------------------------------------------------
| Handshakes (Inspired from CodeIgniter)
|--------------------------------------------------------------------------
|
| A handshake allows you to extend the ZigPHP core by running a script, 
| function, or method at multiple points during system execution. 
| In order to use handshakes, ENABLE_HANDSHAKES must be set to TRUE. 
|
*/
config::set('ENABLE_HANDSHAKES', FALSE);

/*
|--------------------------------------------------------------------------
| URI Settings 
|--------------------------------------------------------------------------
|
| Allow values for URI_TYPE = HTACCESS or FANCY
| HTACCESS means that you are using ZigPHP's included .htacces file
| FANCY means you aren't using a .htaccess file so ZigPHP will use its 
| built in fancy URLs. 
|
*/
config::set('URI_TYPE', 'HTACCESS');

/*
|--------------------------------------------------------------------------
| URI Re-routing 
|--------------------------------------------------------------------------
|
| ZigPHP allows you to let you define special routes that pertain to a 
| specified controller and method. For example you may want http:/yousite.com/join 
| to route to the users controller with the join method. Adding the re-routing 
| rule will allow you to have the url http:/yousite.com/join instead of 
| http:/yousite.com/users/join.
| 
| To use URI Re-routing, URI_REROUTING must be set to TRUE.
|
| Syntax: $URI_reroutes[] = array(
|	'route' => 'ROUTE_FROM_URI', 
|	'controller' => 'CONTROLLER_TO_CALL', 
|	'method' => 'METHOD_TO_CALL'
|	);
|
| ex. $URI_reroutes[] = array(
|	'route' => 'join',
| 	'controller' => 'users', 
|	'method' => 'join'
|	);
|
| NOTE: At the moment, arguments cannot be passed into the re-routes and 
| regex cannot be used for catching routes to re-route. 
|
*/
config::set('URI_REROUTING', FALSE);

// DO NOT EDIT THE LINE BELOW.
$URI_reroutes = array();

// Enter re-routes below.
$URI_reroutes[] = array(
		'route' => 'pro', 
		'controller' => 'test', 
		'method' => 'index'
		);

// DO NOT EDIT THE LINE BELOW. 
config::set('URI_REROUTES', $URI_reroutes);


// END of Application Config
// Filename: app.config.php 
// Location: root/appliction/config/