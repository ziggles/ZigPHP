<?php

/*
| ======================================================================
| ZigPHP Front Controller
| ======================================================================
|
| The front controller is in charge of loading the required classes and 
| executing the user request.
|
*/

session_start();

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
log_debug('-----System Initialization-----'); 

/*
|--------------------------------------------------------------------------
| Load the page in charge of setting up the environment and error reporting 
|--------------------------------------------------------------------------
|
*/
require SYS_PATH . 'zigphp_env' . EXT;

/*
|--------------------------------------------------------------------------
| Load the ZigPHP Super Variable (registry)
|--------------------------------------------------------------------------
|
*/
$zig = load_class('registry');

function get_instance()
{
	return registry::get_instance();
}

/*
|--------------------------------------------------------------------------
| Load the template class
|--------------------------------------------------------------------------
|
*/
$zig->template = load_class('template');


/*
|--------------------------------------------------------------------------
| Load the dev tools class and mark beginning exeuction time
|--------------------------------------------------------------------------
|
*/
$zig->dt = load_class('devtools');
$zig->dt->markTime('execution_start');

/*
|--------------------------------------------------------------------------
| Load the router
|--------------------------------------------------------------------------
|
*/
$zig->router = load_class('router');

/*
|--------------------------------------------------------------------------
| Load the rest of the required classes
|--------------------------------------------------------------------------
|
*/
$zig->security = load_class('security');
$zig->session = load_class('session');
$zig->URI = load_class('URI_helper');
$zig->cache = load_class('cache');
$zig->load = load_class('load');


//-----------------------------------------------------------------------//
/*
| Alright now that we have everything loaded, it's time to run some tasks.
*/

// If cookies are enabled, load the cookies class.
if (config::get('ENABLE_COOKIES') == TRUE) 
{
	$zig->cookies = load_class('cookies');
}

// Check to see if handshakes are enabled.
if (config::get('ENABLE_HANDSHAKES') == TRUE) 
{
	$zig->load->config('handshakes');
	$handshakes = config::get('HANDSHAKES');
	
	load_class('handshakes', FALSE);
	$hh = new handshakes($handshakes);
	
	// Check and run pre system handshakes 
	$hh->runPreSysExec();
}

// If multiple languages are enabled, load the language class. 
if (config::get('ENABLE_MULTI_LANG') == TRUE) 
{
	$zig->lang = load_class('lang');
}

// Do we have a cached version of the request?
if ($zig->cache->ifCacheExists())
{;
	// If so, let's load up that cache.
	$zig->cache->serveCache();
}
else
{
	// If not, let's load some more classes required for normal system execution.
	load_class('base_controller', FALSE);
		
	// Make any libraries or models that need to be autoladed available to the registry.
	$zig->load->autoload();
		
	// Begin output buffering for caching.
	$zig->cache->open();
	
	// Run any pre_controller handshakes 
	if (config::get('ENABLE_HANDSHAKES') == TRUE) 
	{
		$hh->runPreController();
	}
	
	// Let's fire up the requested controller and run the requested method.
	$zig->router->loadController();
	$zig->security->filter_post_data();
	$zig->router->runController();
	
	// Run any post_controller handshakes 
	if (config::get('ENABLE_HANDSHAKES') == TRUE) 
	{
		$hh->runPostController();
	}
	
	// Once the controller has done its thing, it's time to output the template buffer to the user. 
	$zig->template->renderPage();
	
	// Let's close the output buffering and write a cached file if needed.
	$zig->cache->close();
}

// Run any post_sys_exec handshakes 
if (config::get('ENABLE_HANDSHAKES') == TRUE) 
{
	$hh->runPostSysExec();
}

// Close the DB connection if it is open.
$zig->load->db(1);
if ($zig->db->is_connected()) 
{
	$zig->db->close();
}

// Add a time stamp for the last user request.
$zig->session->set('last_update', time());

// Mark the final execution time. 
$zig->dt->markTime('execution_end');

// If we have dev reporting turned to TRUE, let's load the dev report. 
if (config::get('SHOW_DEV_REPORT') === TRUE && config::get('APP_ENV') == 'DEV')
{
	$zig->dt->showDevReport();
}

// And we are done! 
log_debug('-----System Execution Complete-----
'); 

// END of ZigPHP Front Controller
// Filename: zigphp.php 
// Location: root/system/