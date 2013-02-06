<?php

require CLI_PATH . 'cli_helper' . EXT;

/*
| ======================================================================
| Zig CLI Wizard
| ======================================================================
|
| Generate code with the ZigPHP CLI Wizard. 
|
*/

class wizard extends cli_helper
{
		
	public function __construct()
	{
		$this->welcome();
	}

	public function welcome()
	{
		$this->write('---------------------------------------------------------');
		$this->write('Welcome to the interactive ZigPHP CLI Wizard');
		$this->write('---------------------------------------------------------');
		$this->write('ZigPHP Version: ' . ZIG_VERSION);
		$this->write('CLI Version: ' . CLI_VERSION);
		$this->write('Working Path: ' . BASE_PATH);
		$this->write('---------------------------------------------------------');
		$this->write('Available options');
		$this->write();
		$this->write('[G] - Generate');
		$this->write('[Q] - Quit');
		$this->write();
		$this->ask('What would you like to do? (G/Q)');
		
		$this->process_initial_do($this->get());
	}
	
	public function process_initial_do($do)
	{
		strtolower($do);
		
		switch ($do)
		{
			case 'g':
				$this->loadShell('generate');
				break;
				
			case 'q':
			case 'quit':
				return;
				break;
				
			case '';
				$this->write('Nothing was entered. Please enter one of the available options.');
				$this->write('To exit this script, type "quit" or "q".');
				$this->ask('What would you like to do? (G/Q)');

				$this->process_initial_do($this->get());
				return;
				break;
			
			default:
				$this->write('Invalid operation entered. Please enter one of the available options.');
				$this->write('To exit this script, type "quit" or "q".');
				$this->ask('What would you like to do? (G/Q)');

				$this->process_initial_do($this->get());
				return;
				break;
		}
	}
	
}

