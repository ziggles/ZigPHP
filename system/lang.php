<?php

/*
| ======================================================================
| ZigPHP Lang Class
| ======================================================================
|
| This class allows you to run your application with multiple languages 
|
*/

Class lang 
{
	private $zig;
	private $langs;
	private $lang_dir;
	public $active;

	public function __construct()
	{
		$this->zig =& get_instance();
		$this->zig->load->config('lang');
		$this->langs = config::get('LANG_LANGS');
		
		$this->editRoute();
		$this->setLangDir();
	}
	
	private function editRoute()
	{
		$uri_parts = $this->zig->uri_parts;
		
		if (array_key_exists($uri_parts[0], $this->langs)) 
		{
			$this->active = $uri_parts[0];
			array_shift($uri_parts);
			$this->zig->uri_parts = $uri_parts;
			$this->zig->base_url = $this->zig->base_url . $this->active . '/';
		}
		else
		{
			header ('Location: ' . $this->zig->base_url . config::get('LANG_DEFAULT') . '/' . $this->zig->route);
		}
	}
	
	private function setLangDir()
	{
		$folder = APP_PATH . 'languages/' . $this->langs[$this->active] . '/'; 
		
		if (!is_dir($folder)) 
		{
			showError($this->langs[$this->active] . ' language directory does not exists in application/lang/.');
		}
		else
		{
			$this->lang_dir = $folder;
		}
	}
	
	public function get($key, $lang='')
	{
		if ($lang != '')
		{
			if (!array_key_exists($lang, $this->langs)) 
			{
				showError($lang . ' is not a supported language. Re-check your lang.config file.');
				return FALSE;
			}
		}
		else
		{
			$lang = $this->active;
		}
		
		$active = $lang;
		$$active = array();
		
		if (strpos($key, ':') !== FALSE) 
		{
			$split = explode(':', $key);
			if ($lang != config::get('LANG_DEFAULT')) 
			{
				include APP_PATH . 'languages/' . $this->langs[$lang] . '/' . $split[0] . EXT;
			}
			else
			{
				include $this->lang_dir . $split[0] . EXT;
			}
		}
		else
		{
			showError('No language file specified.');
			return FALSE;
		}		
				
		$return = $split[1];			
		return ${$active}[$return];
		
	}
		
}

// END of ZigPHP Lang Class
// Filename: lang.php 
// Location: root/system/