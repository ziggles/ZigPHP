<?php

/*
| ======================================================================
| ZigPHP Base Model
| ======================================================================
| 
| The base model's main fucntion is to create an interface between 
| the zig registry and the models. 
|
*/

Class baseModel 
{

	public $vars = array(); 
	public $zig;

	function __construct() 
	{
		$this->zig =& get_instance();
		$this->load->db();
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

}

// END of ZigPHP Base Model
// Filename: base_model.php 
// Location: root/system/
