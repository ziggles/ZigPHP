<?php

/*
| ======================================================================
| Zig CLI Generate Class
| ======================================================================
|
| This class will generate requested MVC files. 
|
*/

class generate 
{
	private $task;
	private $options;
	
	public function __construct($options)
	{	
		$this->init($options);
		$this->runTask();
	}

	public function init($args)
	{
		if (!is_array($args)) 
		{
			echo 'Error running generate script: Arguments must be an array.';
			die();
		}

		if (isset($args[0]))
		{
			$this->task = $args[0];
			$options = array();

			foreach ($args as $key => $value) 
			{
				if ($key == 0) 
				{
				}
				else
				{
					$options[] = $value;
				}
			}
			$this->options = $options;
		}
	}
	
	public function runTask()
	{
		$task = $this->task;
		
		switch ($task) 
		{		
			case 'controller':
			case 'c':
				$this->controller();
				break;
				
			case 'skin':
			case 's':
				$this->skin();
				break;
				
			case 'view':
			case 'v':
				$this->view();
				break;
			
			case 'help':
			case 'h':
				$this->help();
				break;
			
			default:
				if ($task == '' OR $task == NULL) 
				{
					echo 'No generate task was entered.';
				}
				else
				{
					echo 'Unknown Command: ' . $task	;
				}
				echo PHP_EOL;
				echo "Type: 'php zig generate help' for assistance";
				break;
		}
	}
	
	public function controller()
	{
		$name = $this->options[0];
		
		if ($name == '' OR $name == NULL) 
		{
			echo 'Error - Controller name not defined.';
			return FALSE;
		}
		
		$write = '<?php
		
Class ' . $name . 'Controller extends baseController
{
	public function index()
	{
		$this->template->setTitle("' . $name . ' Index");
		$this->template->render("' . $name . '/' . $name . '_index");
	}
';
		unset($this->options[0]);
		$methods = array();

		if (isset($this->options[1])) 
		{
			foreach ($this->options as $key => $value) 
			{
				$arg_names = array();
				if (strpos($value, ':') !== FALSE)
				{
					$args = explode('-', $value);
					foreach($args as $arg)
					{
						if (strpos($arg, ':') !== FALSE) 
						{
							$split = explode(':', $arg);
							$fun_name = $split[0];
							$arg_names[] = $split[1];
						}
						else
						{
							$arg_names[] = $arg;
						}
					}
				}
				else
				{
					$fun_name = $value;
				}
				
				$count = count($arg_names);
				$start = 1;
				$args_final;
				foreach ($arg_names as $names) 
				{
					if ($count == $start) 
					{
						$args_final .= '$' . $names . "=''";
					}
					else
					{
						$args_final .= '$' . $names . "='', ";
					}
					$start++;
				}
				
				$methods[] = $fun_name;
				
				$write .= '

	public function ' . $fun_name . '(' . $args_final . ')
	{
		$this->template->setTitle("' . $fun_name . ' Page");
		$this->template->render("' . $name . '/' . $name . '_' . $fun_name . '");
	}
';				
				unset($arg_names);
				unset($args_final);
				unset($count);
				unset($start);
			}
		}
		
	$write .= '
}

// End of ' . $name . ' Controller
// Filename: ' . $name . '.php';

		$filename = APP_PATH . 'controllers/' . $name . 'Controller' . EXT;
		file_put_contents($filename, $write);
		echo $name . ' controller created: ' . $filename;
		echo PHP_EOL;
	
		$view_directory = APP_PATH . 'template/' . config::get('ACTIVE_SKIN') . '/views/' . $name;
		if (is_dir($view_directory)) 
		{
		}
		else
		{
			mkdir($view_directory);
			echo $name . ' view folder created: ' . $view_directory;
			echo PHP_EOL;
		}
	
		file_put_contents($view_directory . '/' . $name . '_index' . EXT, 'Index File');
		echo 'index view created: ' . $view_directory . '/' . $name . '_index' . EXT;
		echo PHP_EOL;
	
		foreach ($methods as $method) 
		{
			file_put_contents($view_directory . '/' . $name . '_' . $method . EXT, $method . ' File');
			echo $method . ' view created: ' . $view_directory . '/' . $name . '_' . $method . EXT;
			echo PHP_EOL;
		}
		
		echo 'Controller generation complete.';
		
	}
	
	
	public function skin()
	{
		$name = $this->options[0];
		
		if (mkdir(APP_PATH . 'template/' . $name)) 
		{
			echo $name . " skin created";
			echo PHP_EOL;
		}
		else
		{
			echo 'Error: could not make directory in application/template/';
			return FALSE;
		}
		
		$directory = APP_PATH . 'template/' . $name . '/';
		
		if (mkdir($directory . 'css') AND mkdir($directory . 'images') AND mkdir($directory . 'js') AND mkdir($directory . 'views')) 
		{
			echo $directory . "css directory created";
			echo PHP_EOL;
			echo $directory . "images directory created";
			echo PHP_EOL;
			echo $directory . "js directory created";
			echo PHP_EOL;
			echo $directory . "views directory created";
			echo PHP_EOL;
		}
		else
		{
			echo 'Error: could not make directories in application/template/' . $name;
			return FALSE;
		}
		
		$write .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- BEGIN HTML -->
<html lang="en">
<!-- BEGIN HEADER -->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo template::getTitle(); ?></title>
	<?php echo template::CSS(\'main.css\'); ?>

</head>
<!-- END HEADER -->
<body>
	<!-- BEGIN PAGE CONTENT -->
	<?php template::renderPageContent(); ?>
	<!-- END PAGE CONTENT -->

</body>
</html>
<!-- END HTML -->

';
		$filename = $directory . 'layout' . EXT;
		file_put_contents($filename, $write);
		
		echo $directory . "layout.php created";
		echo PHP_EOL;
		
		fopen($directory . 'css/main.css', 'w');
		
		echo $directory . "css/main.css created";
		
	}
	
	public function view()
	{
		$filename = $this->options[0];
		$skin = $this->options[1];
		
		if (isset($this->options[2])) 
		{
			$directory = APP_PATH . 'template/' . $skin . '/views/' . $this->options[2] . '/';
		}
		else
		{
			$directory = APP_PATH . 'template/' . $skin . '/views/';
		}	
		
		if (!is_dir($directory)) 
		{
			echo "Error: " . $directory . 'doesn\'t exist.';
			return FALSE;
		}
		
		file_put_contents($directory . $filename . EXT, $filename . ' generated view');
		echo $directory . $filename . EXT . ' view was created';
	}
	
	public function help()
	{
		echo <<<HELP
--------------------------------------------------------------------------
Zig CLI - Generate Help
--------------------------------------------------------------------------

Usage: 
	php zig [generate|g <controller|c|model|m|view|v> <options>]

Options:
	For controllers:
		syntax: controller|c <name> <method1> <method2> <method3:argument1-arugment2>	
		name: the name of the controller in singular form.  
		method: simply type the method name to add it to the controller. Append a colon (:)
		to the method name to add arguments [seperate arguments by adding a dash (-)]
		
		NOTES: 
		- An index method is automatically added when a controller is generated.  
		- Generating a controller will automatically generate a view folder and view for each method.
		- Created views will be made in the ACTIVE_SKIN directory set by the app.config.
	
	For skins:
		syntax: skin|s <name_of_skin>
		name_of_skin: The name of the skin you want to create. 
		
		NOTES:
		- A css, images, js, and views directories will be created.	
		- A basic layout.php file and a blank main.css file will be created.
		
Examples:
	php zig generate controller blog
	php zig generate controller blog page article:id
	php zig g c blog page article:id
	
	php zig generate skin my_awesome_skin
	php zig g s my_awesome_skin

Documentation: 
	http://zigphp.com/docs/cli
HELP;
	}
	
}

// END of Zig CLI Generate Class
// Filename: generate.php 
// Location: root/system/zig_cli/