<?php

/*
| ======================================================================
| ZigPHP Form Library Config
| ======================================================================
|
*/

$zig =& get_instance();

/*
|--------------------------------------------------------------------------
| Default Error Messages
|--------------------------------------------------------------------------
|
| If no custom error message has been set for a field, the form library will use these default message to display to the user.
| &name is replaced with the human name set in the validation rule.
| &value is replaced with the given parameter set in the validation rule.
|
| NOTE : For FORM_EROMES_MUST_MATCH, it is highly recommended you set your own custom message. 
|
*/

config::set('FORM_EROMES_REQUIRED', $zig->lang->get('errors:FORM_EROMES_REQUIRED'));
config::set('FORM_EROMES_MIN_LENGTH', $zig->lang->get('errors:FORM_EROMES_MIN_LENGTH'));
config::set('FORM_EROMES_MAX_LENGTH', $zig->lang->get('errors:FORM_EROMES_MAX_LENGTH'));
config::set('FORM_EROMES_EXACT_LENGTH', $zig->lang->get('errors:FORM_EROMES_EXACT_LENGTH'));
config::set('FORM_EROMES_INBETWEEN', $zig->lang->get('errors:FORM_EROMES_INBETWEEN'));
config::set('FORM_EROMES_EMAIL', $zig->lang->get('errors:FORM_EROMES_EMAIL'));
config::set('FORM_EROMES_MUST_MATCH', $zig->lang->get('errors:FORM_EROMES_MUST_MATCH')); 

config::set('FORM_EROMES_GENERAL', $zig->lang->get('errors:FORM_EROMES_GENERAL')); 

// END of ZigPHP Form Config
// Filename: form.config.php 
// Location: root/application/config/