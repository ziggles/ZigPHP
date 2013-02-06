<?php

/*
| ======================================================================
| Zig CLI Class
| ======================================================================
|
| This class handles the execution of a Zig CLI command
|
*/

class zig
{	
	private $task; 
	private $options;
	
	public function __construct($args)
	{
		$this->init($args);
		$this->runTask();
	}
	
	public function init($args)
	{
		if (!is_array($args)) 
		{
			echo 'Error initializing script.';
			die();
		}
		
		if (empty($args[1])) 
		{
			$this->task = 'empty';
		}
		
		if (isset($args[1]))
		{
			$this->task = $args[1];
			$options = array();
			
			foreach ($args as $key => $value) 
			{
				if ($key == 0 OR $key == 1) 
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
			case 'g':
			case 'generate':
				$this->generate();	
				break;
				
			case '-v':
				$this->version();
				break;
				
			case 'help':
			case 'h':
				$this->help();
				break;
			
			case 'empty':
				$this->wizard_start();
				break;
			
			default:
				echo 'Unknown Command: ' . $task	;
				echo PHP_EOL;
				echo "Type: 'php zig help' for assistance";
				break;
		}
	}
	
	public function generate()
	{
		require CLI_PATH . 'generate' . EXT;
		
		$g = new generate($this->options);
	}
	
	public function version()
	{
		echo 'ZigPHP Version: ' . ZIG_VERSION;
		echo PHP_EOL;
		echo 'Zig CLI Version: ' . CLI_VERSION;
		echo PHP_EOL;
		echo "PHP Version: " . phpversion();
	}
	
	public function wizard_start()
	{
		require CLI_PATH . 'wizard' . EXT;
		
		$wizard = new wizard();
	}
	
	public function help()
	{
		echo <<<HELP
--------------------------------------------------------------------------
Zig CLI - Help
--------------------------------------------------------------------------

The Zig CLI allows you to quickly generate controllers, models, and views 
to make development even faster. You can generate simple empty controllers 
and models or use the Zig CLI to generate methods in controllers and CRUD 
functionality in models. 

Usage: 
	php zig [generate|g <options>]
	
	example: php zig generate controller blog 
	
	type: 'php zig generate help' for more examples and options
	
Documentation: 
	http://zigphp.com/docs/cli
HELP;
	}
}

// END of Zig CLI Class
// Filename: zig_cli.php 
// Location: root/system/zig_cli/