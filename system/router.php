<?php

/*
| ======================================================================
| ZigPHP Router 
| ======================================================================
|
| This class will route the URI request to the required controller. 
|
*/

class router
{

	private $zig;
	private $path;
	public $file;
	public $controller;
	public $controller_instance;
	public $action; 
	public $arguments; 
	private $disable_controller = FALSE;

	public function __construct()
	{
		$this->zig =& get_instance();
		$this->path = APP_PATH . 'controllers/';
	}

	 public function loadController($instantiate = TRUE)
	 {
		$this->getController();
		
		if (is_readable($this->file) === FALSE)
		{
			log_debug('Controller doesn\'t exist - Error 404');
			$this->file = $this->path . '/errorController.php';
			$this->controller = 'error';
			$this->action = 'error404';
		}
		
		if ($instantiate === TRUE)
		{
			log_debug('Controller set: ' . $this->controller);
			log_debug('Method set: ' . $this->action);

			include $this->file;
	
			$class = $this->controller . 'Controller';
			$controller = new $class();
			$this->controller_instance = $controller; 
			
			if (is_callable(array($controller, $this->action)) == false)
			{
				$this->action = 'index';
			}
	
			if (method_exists($controller, 'setup'))
			{
				$controller->setup();
			}
		}

		$arguments = array();
		if($this->zig->uri_parts != '')
		{
			foreach ($this->zig->uri_parts as $key => $val) 
			{
				if ($key == 0 || $key == 1)
				{
				}
				else
				{
					$arguments[$key] = $val;
				}
			}
		}

		$this->arguments = $arguments;
	}

	private function getController() 
	{
		$route = $this->zig->route;
		$parts = $this->zig->uri_parts;
		$start_uri = 0;
		
		if (empty($route))
		{
			$route = config::get('DEFAULT_CONTROLLER');
			log_debug('URI is empty. Reverting to default controller');
		}
		else
		{
			$this->controller = $parts[$start_uri];
			if(isset($parts[$start_uri+1]))
			{
				$this->action = $parts[$start_uri+1];
			}
			
			if (config::get('URI_REROUTING') === TRUE)
			{
				$uri_reroutes = config::get('URI_REROUTES');
				foreach ($uri_reroutes as $reroute)
				{
					if ($route == $reroute['route'])
					{
						$this->controller = $reroute['controller'];
						$this->action = $reroute['method'];
						log_debug('URI is a re-route');
					}
				}
			}
		}
		
		if (empty($this->controller))
		{
			$this->controller = config::get('DEFAULT_CONTROLLER');
		}

		if (empty($this->action))
		{
			$this->action = 'index';
		}
		
		$this->file = $this->path . '/' . $this->controller . 'Controller.php';
	}

	public function runController()
	{
		if ($this->disable_controller === TRUE) 
		{
			return;
		}
		
		log_debug('Controller method called');
		call_user_func_array(array($this->controller_instance, $this->action), $this->arguments);
	}
	
	public function disableController()
	{
		$this->disable_controller = TRUE;
	}

}

// END of ZigPHP Router
// Filename: router.php 
// Location: root/system/