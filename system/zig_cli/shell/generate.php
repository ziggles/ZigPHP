<?php

/**
* Zig CLI - Generate Shell
*/
class generate extends wizard
{
	
	function __construct()
	{
		$this->generate_welcome();
	}
	
	public function generate_welcome()
	{
		$this->write();
		$this->write('---------------------------------------------------------');
		$this->write('ZigPHP CLI - Generate Shell');
		$this->write('---------------------------------------------------------');
		$this->write('Use this shell script to generate code for your ZigPHP Applications');
		$this->write('---------------------------------------------------------');
		$this->write('Available options');
		$this->write();
		$this->write('[C] - Controller');
		$this->write('[S] = Skin');
		$this->write('[Q] - Quit back to ZigPHP CLI wizard');
		$this->write();
		$this->ask('What would you like to do? (C/S/Q)');
		
		$this->process_generate_initial_do($this->get());
	}
	
	public function process_generate_initial_do($do)
	{
		strtolower($do);
		
		switch ($do)
		{
			case 'c':
				$this->write('---------------------------------------------------------');
				$this->write('Generate a Controller');
				$this->write('---------------------------------------------------------');
				$this->write('NOTE: An index method is automatically created when a controller is generated');
				$this->write();
				$this->controller_name();
				break;
				
			case 's':
				$this->write('---------------------------------------------------------');
				$this->write('Generate a Skin');
				$this->write('---------------------------------------------------------');
				$this->write('NOTE: A css, images, js, and views directories will be created.');	
				$this->write();
				$this->skin_name();
				break;
				
			case 'q':
			case 'quit':
				$this->write();
				$this->welcome();
				return;
				break;
				
			case '';
				$this->write('Nothing was entered. Please enter one of the available options.');
				$this->write('To exit this script, type "quit" or "q".');
				$this->ask('What would you like to do? (C/S/Q)');

				$this->process_generate_initial_do($this->get());
				return;
				break;
			
			default:
				$this->write('Invalid operation entered. Please enter one of the available options.');
				$this->write('To exit this script, type "quit" or "q".');
				$this->ask('What would you like to do? (C/S/Q)');

				$this->process_generate_initial_do($this->get());
				return;
				break;
		}
	}
	
	/**
	* Methods to generate a controller
	*/
	
	public function controller_name()
	{
		$this->input = array();
		$this->ask('What is the name of your controller?');
		
		$name = $this->get();
		if ($name == '' OR empty($name)) 
		{
			$this->write('No name entered');
			$this->controller_name();
			return;
		}
		else
		{
			// A controller name was enterred and we create a blank array to put in the controller methods
			$this->input['controller_name'] = $name;
			$this->input['controller_methods'] = array();
			$this->input['controller_methods'][] = 'index';
			$this->controller_methods();
		}
	}
	
	public function controller_methods()
	{		
		$this->ask('Would you like to create methods? [y/n]', 'y');
		$answer = strtolower($this->get());
		
		if ($answer == 'y' OR empty($answer) OR $answer == '') 
		{
			$this->write('');
			$this->ask('');
		}
		else
		{
			return;
		}
		
		showVars($this->input);
	}
	
	/**
	* Methods to generate a skin
	*/
	
	public function skin_name()
	{
		$this->input = array();
		$this->ask('What is the name of your skin?');
		
		$name = $this->get();
		if ($name == '' OR empty($name)) 
		{
			$this->write('No name entered');
			$this->skin_name();
			return;
		}
		else
		{
			// A controller name was enterred and we create a blank array to put in the controller methods
			$this->input['skin_name'] = $name;
			$this->skin_options();
		}
	}
	
	public function skin_options()
	{
		$this->ask('Do you want to create a basic layout.php? [y/n]', 'y');
		$answer = strtolower($this->get());
		
		if ($answer == 'y' OR empty($answer) OR $answer == '') 
		{
			$this->input['skin_layout'] = 'yes';
		}
		else
		{
			$this->input['skin_layout'] = 'no'; 
		}
		
		$this->ask('Do you want to create a blank main.css? [y/n]', 'y');
		$answer = strtolower($this->get());
		
		if ($answer == 'y' OR empty($answer) OR $answer == '') 
		{
			$this->input['skin_css'] = 'yes';
		}
		else
		{
			$this->input['skin_css'] = 'no'; 
		}
		
		$this->skin_review_options();
	}
	
	public function skin_review_options()
	{
		$this->write('---------------------------------------------------------');
		$this->write('Please review the information below to see if it is correct');
		$this->write();
		$this->write('Skin Name: ' . $this->input['skin_name']);
		$this->write('Create Layout.php: ' . $this->input['skin_layout']);
		$this->write('Create main.css: ' . $this->input['skin_css']);
		$this->write();
		$this->ask('Is the above information correct? [y/n]', 'y');
		
		$answer = strtolower($this->get());
		
		if ($answer == 'y' OR empty($answer) OR $answer == '') 
		{
			$this->skin_generate();
		}
		else
		{
			$this->skin_name();
		}
	}
	
	public function skin_generate()
	{
		$name = $this->input['skin_name'];
		
		$directory = APP_PATH . 'template/' . $name . '/';
		
		if (is_dir($directory)) 
		{
			$this->write($name . ' skin directory already exists.');
			$this->write('WARNING: Overriding the directory will delete all the contents of the current directory.');
			$this->ask('Would you like to override the current directory? [y/n]', 'n');
			$answer = strtolower($this->get());

			if ($answer == 'y') 
			{
				$this->deleteDirectory($directory);
				$this->write('Old skin directory removed...');
			}
			else
			{
				$this->write('No skin was created... returning to ZigPHP CLI wizard start.');
				$this->write();
				$this->welcome();
				return;
			}
		}

		if (mkdir($directory)) 
		{
			$this->write('Skin directory created...');
		}
		else
		{
			$this->write('Error: could not make directory in application/template/');
			return FALSE;
		}

		if (mkdir($directory . 'css') AND mkdir($directory . 'images') AND mkdir($directory . 'js') AND mkdir($directory . 'views')) 
		{
			$this->write('Skin Directory: ' . $directory);
			$this->write("css directory created...");
			$this->write("images directory created...");
			$this->write("js directory created...");
			$this->write("views directory created...");
		}
		else
		{
			$this->write('Error: could not make directories in application/template/' . $name);
			return FALSE;
		}
		
		if ($this->input['skin_layout'] == 'yes') 
		{
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

			$this->write("layout.php created...");
		}
		
		if ($this->input['skin_css'] == 'yes') 
		{
			fopen($directory . 'css/main.css', 'w');

			$this->write("main.css created...");
		}
		
		$this->write('Skin generation complete... returning to ZigPHP CLI wizard start.');
		$this->write();
		$this->welcome();
	}
	
}
