<?php

/*
| ======================================================================
| ZigPHP URI Helper Class
| ======================================================================
|
| This class handles all the URI information aswell as formatting pretty URLs  
|
*/

class URI_helper
{
	
	private $zig;
	
	public function __construct()
	{
		$this->zig =& get_instance();
		$this->setRoute();
		$this->fixRoute();
		$this->zig->uri_parts = $this->zig->security->filterData($this->zig->uri_parts);
	}
	
	private function fixRoute()
	{
		$route = $_SERVER['REQUEST_URI'];
							
		if (config::get('URI_TYPE') == 'FANCY')
		{
			if (strpos($route, 'index.php') == FALSE)
			{
				$site_url = config::get('SITE_URL');
				$site_url = parse_url($site_url);
				$path = substr($site_url['path'], 1);
				$real_route = str_replace($path, '', $this->zig->route);
				$this->zig->route = $real_route;
				$link = $this->zig->base_url . $real_route;
				
				header ('Location: ' . $link);
			}
		}
		
	}
	
	private function setRoute()
	{
		$uri_type = config::get('URI_TYPE');
		
		if ($uri_type == 'HTACCESS')
		{
			$this->zig->base_url = config::get('SITE_URL');
			
			$route = $_SERVER['REQUEST_URI'];
			
			if (strpos($route, 'index.php') !== FALSE)
			{
				$site_url = config::get('SITE_URL');
				$site_url = parse_url($site_url);
				$real_route = str_replace($site_url['path'] . 'index.php', '', $route);	
				$real_route = substr($real_route, 1);
				
				$this->zig->route = $real_route;
			
				$parts = explode('/', $real_route);
				
				$this->zig->uri_parts = $parts;
			}
			else
			{
				$real_route = (empty($_GET['rt'])) ? '' : $_GET['rt'];
				
				$this->zig->route = $real_route;
				
				$parts = explode('/', $real_route);
				
				$this->zig->uri_parts = $parts;		
			}
		}
		elseif ($uri_type == 'FANCY')
		{
			$this->zig->base_url = config::get('SITE_URL') . 'index.php/';
			
			$route = $_SERVER['REQUEST_URI'];
			$site_url = config::get('SITE_URL');
			
			if ($site_url == '')
			{
				$this->zig->route = '';
				$this->zig->uri_parts = array();
				return;
			}
			
			$site_url = parse_url($site_url);
			$real_route = str_replace($site_url['path'] . 'index.php', '', $route);
			$real_route = substr($real_route, 1);
			
			$this->zig->route = $real_route;
			
			$parts = explode('/', $real_route);
			
			$this->zig->uri_parts = $parts;
		}
				
		log_debug('URI Type is '. $uri_type . ' and route set as: ' . $real_route);
	}
	
}

// END of ZigPHP URI Helper Class
// Filename: URI_helper.php 
// Location: root/system/