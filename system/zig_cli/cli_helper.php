<?php

/**
* Zig CLI Helper
*/
class cli_helper
{
	
	private $input = array();

	public function write($message='')
	{
		fwrite(STDOUT, $message . "\r\n");
	}
	
	public function get()
	{
		return trim(fgets(STDIN));
	}
	
	public function ask($message, $default_val='')
	{
		if ($default_val == '') 
		{
			fwrite(STDOUT,  $message . "\n > ");
		}
		else
		{
			fwrite(STDOUT, $message . "\n[" . $default_val . "] > ");
		}
	}
	
	public function loadShell($name)
	{
		$path = CLI_PATH . 'shell/' . $name . EXT;
		require_once $path;
		
		$instance = new $name();
		return $instance;
	}
	
	public function deleteDirectory($directory, $empty = false) 
	{ 
	    if(substr($directory,-1) == "/") 
		{ 
	        $directory = substr($directory,0,-1); 
	    } 

		if(!file_exists($directory) || !is_dir($directory)) 
		{ 
			return false; 
		} 
		elseif(!is_readable($directory)) 
		{ 
			return false; 
		} 
		else 
		{ 
			$directoryHandle = opendir($directory); 

			while ($contents = readdir($directoryHandle)) 
			{ 
				if($contents != '.' && $contents != '..') 
				{ 
		    		$path = $directory . "/" . $contents; 

		    		if(is_dir($path)) 
					{ 
		        		$this->deleteDirectory($path); 
		    		} 
					else 
					{ 
		        		unlink($path); 
		    		} 
				} 
			} 

			closedir($directoryHandle); 

			if($empty == false) 
			{ 
				if(!rmdir($directory)) 
				{ 
		    		return false; 
				} 
			} 

			return true; 
		} 
	}

}
