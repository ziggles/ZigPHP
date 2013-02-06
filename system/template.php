<?php

/*
| ======================================================================
| ZigPHP Template Class 
| ======================================================================
|
| This class handles all rendering and maniuplation to templates and views. 
|
*/

Class template 
{
	private $zig;
	private $layout;
	public $skin_path;
	public $skin_path_uri;
	private $error;
	public $title;
	private $layout_path;
	public $template_buffer = array();
	private $vars = array();

	protected static $template;

	function __construct() 
	{
		$this->zig =& get_instance();
		$this->setActiveSkin();
	}
	
	public function setActiveSkin($skin='')
	{
		if ($skin == '')
		{
			$this->skin_path = APP_PATH . 'template/' . config::get('ACTIVE_SKIN') . '/';
			$this->skin_path_uri = config::get('SITE_URL') . APP_FOLDER . '/template/' . config::get('ACTIVE_SKIN') . '/';
			
			log_debug('Active skin set to ' . config::get('ACTIVE_SKIN'));
		}
		else
		{
			if (is_dir(APP_PATH . 'template/' . $skin . '/'))
			{
				$this->skin_path = APP_PATH . 'template/' . $skin . '/';
				$this->skin_path_uri = config::get('SITE_URL') . APP_FOLDER . '/template/' . $skin . '/';
				
				log_debug('Active skin set to ' . $skin);
			}
			else
			{
				showError('The skin <b>'.$skin.'</b> could not be found in the template folder.', 'setActiveSkin');
			}
		}
	}

	public function set($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function get($name)
	{
		if (!isset($this->vars[$name]))
		{
			return FALSE;
		}
		return $this->vars[$name];
	}

	public function hideLayout($off = 1)
	{
			$this->layout = ($off == 1) ? 'off' : 'on';
			log_debug('Layout set to ' . $this->layout);
	}

	public function setError($on = 1)
	{
			$this->error = ($on == 1) ? 'yes' : 'no';
	}

	public function render($name, $lang='')
	{
		if ($this->error == 'yes')
		{
			return false;
		}
		
		if ($lang != '') 
		{
			$path = $this->skin_path . 'views/' . $name . '_' . $lang . EXT;
			$common_path = APP_PATH . 'template/common/views/' . $name . '_' . $lang . EXT;
		}
		else
		{
			$path = $this->skin_path . 'views/' . $name . EXT;
			$common_path = APP_PATH . 'template/common/views/' . $name . EXT;
		}
		
		if (file_exists($path))
		{
			$this->template_buffer[$name] = $path;       
		}
		elseif (file_exists($common_path))
		{
			$this->template_buffer[$name] = $common_path;    
		}
		else
		{
			showError('The template ' . $name . ': ' . $lang . ' could not be found in ' . $path . '.', 'render');
			return false;
		}
		
		log_debug($name . ' view added to template buffer');
	}
	
	public function unsetPage($name='')
	{
		if ($name == '')
		{
			foreach ($this->template_buffer as $key => $val) 
			{
				unset ($this->template_buffer[$key]);
			}
			
			log_debug('Template buffer unset');
		}
		else
		{
			unset ($this->template_buffer[$name]);
			log_debug($name . 'template unset');
		}
	}

	public function loadLayout()
	{
		if ($this->layout == 'off')
		{
			$this->renderPageContent();
			return;
		}
		else
		{
			$this->set('zig', $this->zig);
			$path = $this->skin_path . 'layout' . EXT;
			$common_path = APP_PATH . 'template/common/layout' . EXT;

			if (file_exists($path))
			{
				if ($this->vars)
				{
					foreach ($this->vars as $key => $value)
					{
						$$key = $value;
					}
				}

				include ($path);       
			}
			elseif (file_exists($common_path))
			{
				if ($this->vars)
				{
					foreach ($this->vars as $key => $value)
					{
						$$key = $value;
					}
				}

				include ($common_path); 
				
				log_debug('Layout loaded');
			}
			else
			{
				showError('A layout could not be found in '.$path.'.', 'loadLayout');
				return false;
			}
		}
	}

	public function renderPage($special = '')
	{
		if ($special == 'dt') 
		{
			$this->loadLayout();
			return;
		}
		
		if (count($this->zig->runtime_errors) > 0) 
		{
			$this->unsetPage();
			$this->showErrors();
		}
		log_debug('Page rendered');
		$this->loadLayout();
	}

	public function renderPageContent()
	{
		$this->set('zig', $this->zig);
		if ($this->vars)
		{
			foreach ($this->vars as $key => $value)
			{
				$$key = $value;
			}
		}
		if ($this->error == 'yes')
		{
			log_debug('Error(s) found. Loading error template');
			
			$path = $this->skin_path . 'views/error' . EXT;
			$common_path = APP_PATH . 'template/common/views/error' . EXT;
	
			if (file_exists($path))
			{
				include ($path);   
			}
			elseif (file_exists($common_path))
			{
				include ($common_path);   
			}
		}
		else
		{
			foreach ($this->template_buffer as $page)
			{
				include ($page);
			}
		}
	}
	
	public function showDevReport($on = 1)
	{
		$action = ($on == 1) ? TRUE : FALSE;
		
		config::set('SHOW_DEV_REPORT', $action);
	}

	public function mainCSS()
	{
		$filepath = $this->skin_path_uri . 'css/style.css';
		return '<link rel="stylesheet" href="' . $filepath . '" type="text/css" >';
	}

	public function templatePath()
	{
		$filepath = $this->skin_path_uri;
		$filepath = $filepath;
		return $filepath;
	}
	
	public function commonPath()
	{
		$filepath = config::get('SITE_URL') . 'application/template/common/';
		return $filepath;
	}

	public function image($filename, $options = '')
	{
		$filepath = $this->skin_path_uri;
		$filepath = $filepath . 'images/' . $filename;
		return '<img src="' . $filepath . '" border="0" alt="' . $filepath . ' ' . $options . '">';
	}

	public function imagePath($filename)
	{
		$filepath = $this->skin_path_uri;
		$filepath = $filepath . 'images/' . $filename;
		return $filepath;
	}

	public function CSS($filename)
	{
		$filepath = $this->skin_path_uri;
		$filepath = $filepath . 'css/' . $filename;
		return '<link rel="stylesheet" href="' . $filepath . '" type="text/css" >
';
	}

	public function commonCSS($filename)
	{
		$filepath = config::get('SITE_URL') . 'application/template/common/';
		$filepath = $filepath . 'css/' . $filename;
		return '<link rel="stylesheet" href="' . $filepath . '" type="text/css" >
';
	}

	public function getTitle()
	{
		return $this->title; 
	}

	public function setTitle($value) 
	{ 
		$this->title = $value; 
	} 
	
	private function showErrors()
	{
		$this->hideLayout();
		
		$errors = $this->zig->runtime_errors;
			
	 	$this->set('errors' , $errors);
		$this->render('error');
		$this->setError();
	}

}

// END of ZigPHP Template Class
// Filename: template.php 
// Location: root/system/