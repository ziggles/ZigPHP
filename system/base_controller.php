<?php

/*
| ======================================================================
| ZigPHP Base Controller
| ======================================================================
| 
| The base controller's main fucntion is to create an interface between 
| the zig registry and the controllers. 
|
*/

Abstract Class baseController 
{

	public $vars = array(); 
	public $zig;

	function __construct() 
	{
		$this->zig =& get_instance();
		$this->load->autoLoadModel();
	}

	public function __set($index, $value)
	{
		$this->vars[$index] = $value;
	}

	public function __get($index)
	{
		if (array_key_exists($index, $this->vars))
		{
			return $this->vars[$index];
		}
		else
		{
			return $this->zig->$index;
		}
	}

	/**
	 * @all controllers must contain an index method
	 */
	abstract function index();

}

// END of ZigPHP Base Controller
// Filename: base_controller.php 
// Location: root/system/
