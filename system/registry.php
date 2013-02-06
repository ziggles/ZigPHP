<?php

/*
| ======================================================================
| ZigPHP Registry 
| ======================================================================
|
| The registry (referred to as the Zig super variable) will hold class 
| instances needed for system execution. 
|
*/

Class Registry 
{

	private $vars = array();
	public static $instance;
	public $runtime_errors = array();
	
	public function __construct()
	{
		self::$instance =& $this;
	}

	public function __set($index, $value)
	{
		$this->vars[$index] = $value;
	}

	public function __get($index)
	{
		return $this->vars[$index];
	}
	
	public static function get_instance()
	{
		return self::$instance;
	}

}

// END of ZigPHP Registry
// Filename: registry.php 
// Location: root/system/