<?php

/*
| ======================================================================
| Handshakes Config
| ======================================================================
|
| Define handshakes. This config file will only load if ENABLE_HANDSHAKES
| is set to TRUE in your app.config. If a page is cached, pre_controller 
| and post_controller handhsakes will not execute. 
|
*/

// DO NOT EDIT THE LINES BELOW.
$handshakes = array(
	'pre_sys_exec' => array(), 
	'pre_controller' => array(),
	'post_controller' => array(), 
	'post_sys_exec' => array()
);

/*
|--------------------------------------------------------------------------
| Define Handshakes
|--------------------------------------------------------------------------
|
| A handshake is a pre or post controller function that you specify to run 
| during system execution. 
|
| NOTE: Database functionality is not available in pre_sys_exec handshakes
|
*/
// Define handshakes below.

$handshakes['post_sys_exec'][] = array(
	'class' => 'test', 
	'file_path' => 'handshakes/test.php', 
	'method' => 'lol'
	);

// Do not enter handshakes below this line.

config::set("HANDSHAKES", $handshakes);

// END of ZigPHP Handshakes Config
// Filename: handshakes.config.php 
// Location: root/application/config/