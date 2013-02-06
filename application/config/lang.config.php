<?php

/*
| ======================================================================
| Language Config
| ======================================================================
|
| Define handshakes. This config file will only load if ENABLE_HANDSHAKES
| is set to TRUE in your app.config. If a page is cached, pre_controller 
| and post_controller handhsakes will not execute. 
|
*/

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| A handshake is a pre or post controller function that you specify to run 
| during system execution. 
|
| NOTE: Database functionality is not available in pre_sys_exec handshakes
|
*/
config::set('LANG_DEFAULT', 'en');

/*
|--------------------------------------------------------------------------
| Set Supported Languages
|--------------------------------------------------------------------------
|
|
*/
$langs = array();

$langs['en'] = 'english';
$langs['es'] = 'spanish';

config::set('LANG_LANGS', $langs);



// END of ZigPHP Handshakes Config
// Filename: handshakes.config.php 
// Location: root/application/config/