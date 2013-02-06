<?php

/*
| ======================================================================
| ZigPHP Load Class
| ======================================================================
|
| This class is in charge of loading and auto-loading models, libraries, 
| and helpers for use in the controller. 
|
*/

class Load
{
	private $zig;

	public function __construct()
	{
		$this->zig =& get_instance();
	}
	
	public function autoload()
	{
		$this->config('db');
		
		if (config::get('DB_AUTOLOAD')) 
		{
			$this->db();
		}
		$libraries = config::get('AUTOLOAD_LIBRARIES');
		if (count($libraries) > 0)
		{
			foreach ($libraries as $library)
			{
				$this->autoloadLibrary($library);
			}
		}
		$helpers = config::get('AUTOLOAD_HELPERS');
		if (count($helpers) > 0)
		{
			foreach ($helpers as $helper)
			{
				$this->autoloadHelper($helper);
			}
		}
	}

	private function autoloadLibrary($name)
	{
		$filename = $name . '.library' . EXT;
		$file = BASE_PATH . '/libraries/' . $filename;
		if (file_exists($file) == FALSE)
		{
			showError('Library <b>'.$name.'</b> was not found in root/libraries/', 'autoloadLibrary');
			return FALSE;
		}
		include ($file);

		$instance = new $name($this->zig);

		if (property_exists($name, 'requires_config') == TRUE)
		{
			$this->config($name);
		}

		$this->zig->$name = $instance;
		
		log_debug($name . ' library auto-loaded');
	}

	public function autoloadModel()
	{
		if (config::get('AUTOLOAD_MODEL') == TRUE) 
		{
			$name = $this->zig->router->controller;
			$filename = $name . 'Model' . EXT;
			$file = APP_PATH . '/models/' . $filename;
			if (file_exists($file) == FALSE)
			{
				return FALSE;
			}
			else
			{
				$this->model();
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	private function autoloadHelper($name)
	{
		$filename = $name . '.helper' . EXT;
		$file = BASE_PATH . '/helpers/' . $filename;
		if (file_exists($file) == FALSE)
		{
			showError('Helper <b>'.$name.'</b> was not found in root/helpers', 'autoloadHelper');
			return FALSE;
		}
		
		include ($file);

		log_debug($name . ' helper auto-loaded');
	}
	
	public function Library($name)
	{
		if (class_exists($name))
		{
			showError('Library <b>'.$name.'</b> already loaded', 'Library');
			return FALSE;
		}	
		
		$filename = $name . '.library' . EXT;
		$file = BASE_PATH . '/libraries/' . $filename;
		if (file_exists($file) == FALSE)
		{
			showError('Library <b>'.$name.'</b> was not found in root/libraries/', 'Library');
			return FALSE;
		}
		
		include ($file);

		$instance = new $name($this->zig);

		if (property_exists($name, 'requires_config') == TRUE)
		{
			$this->config($name);
		}
		
		$this->zig->$name = $instance;
		
		log_debug($name . ' library loaded');
	}
	
	public function model($name = '')
	{
		if ($name == '') 
		{
			$name = $this->zig->router->controller;
		}
		
		if (class_exists($name))
		{
			showError('Model <b>'.$name.'</b> already loaded', 'model');
			return FALSE;
		}	
		
		$filename = $name . 'Model' . EXT;
		$file = APP_PATH . '/models/' . $filename;
		if (file_exists($file) == FALSE)
		{
			showError('Model <b>'.$name.'</b> was not found in application root/models/', 'model');
			return FALSE;
		}
		
		require_once 'base_model' . EXT;
		
		include $file;
		
		$instance_name = $name . 'Model';

		$instance = new $instance_name();
		
		$this->zig->model = $instance;
		
		log_debug($name . ' model loaded');
	}
	
	public function helper($name)
	{
		$filename = $name . '.helper' . EXT;
		$file = BASE_PATH . '/helpers/' . $filename;
		if (file_exists($file) == FALSE)
		{
			showError('Helper <b>'.$name.'</b> was not found in root/helpers', 'autoloadHelper');
			return FALSE;
		}
		$i_files = get_included_files();
		
		foreach ($i_files as $i_file)
		{
			if ($i_file == $file)
			{
				showError('Helper <b>'.$name.'</b> already loaded', 'helper');
				return FALSE;			
			}
		}
		include ($file);

		log_debug($name . ' helper loaded');
	}

	public function config($name)
	{
		$filename = $name . '.config' . EXT;
		$file = APP_PATH . 'config/' . $filename;
		if (file_exists($file) == false)
		{
			showError('Config file <b>'.$name.'</b> was not found in root/application/config/', 'loadConfig');
			return FALSE;
		}
		include ($file);
		log_debug($name . ' config loaded');
	}
	
	public function db($return_if_false = 0)
	{
		if ($this->zig->db)
		{
			return;
		}
		require SYS_PATH . 'db/database' . EXT; 
		$this->zig->db = db_instance($return_if_false);
	}

}

// END of ZigPHP LLoad Class
// Filename: load.php 
// Location: root/system/