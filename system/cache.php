<?php

/*
| =========================================
| ZigPHP Cache Class
| =========================================
|
| This class is in charge of page caching. 
| At the moment, this class will only cache entire pages so it is not recommended for views with dynamic content in the headers and footers. 
|
*/

class Cache
{
	
	private $zig;
	private $filename;
	private $cache_page = 0;
	public $cache_exists;
	
	public function __construct()
	{
		$this->zig = get_instance(); 
	}
	
	public function open()
	{
		ob_start();
	}
	
	public function close()
	{
		$contents = ob_get_contents();
		ob_end_flush();
		
		if ($this->cache_page > 0)
		{
			$this->writeCache($contents);
		}
	}
	
	public function cachePage($time = 0)
	{
		$this->cache_page = (is_numeric($time)) ? $time : 0;
	}
	
	public function serveCache()
	{
		log_debug('Loading cached file');
		include $this->filename;
	}
	
	private function getFileName()
	{
		$filename = $this->zig->router->controller . '_' . $this->zig->router->action;
		$arguments = $this->zig->router->arguments;
		
		if (count($arguments) > 0)
		{
			foreach ($arguments as $arg)
			{
				$filename .=  '_' . $arg;
			}
		}
		
		$filename .= '.html';		
		
		$this->filename = BASE_PATH . '/cache/' . $filename;
	}
	
	public function ifCacheExists()
	{
		log_debug('Checking if cached file exists');
		$this->zig->router->loadController(FALSE);
		$this->getFileName();
		
		$file = $this->filename;
		
		if (file_exists($file))
		{
			$fh = fopen($file, 'r');
			$contents = fread($fh, 50);
			preg_match("/<!-- cache:(\d+) -->/", $contents, $matches);
			
			$file_time = filemtime($file) + $matches[1];
			
			fclose($fh);
			
			if (time() > $file_time)
			{
				if (unlink($file) === FALSE)
				{
					showError('Unable to delete cached file' . $file, 'ifCacheExists');
				}
				log_debug('Cached file has expired');
				$this->cache_exists = FALSE;
				return FALSE;
			}
			
			log_debug('Cached file exists');
			$this->cache_exists = TRUE;
			return TRUE;
		}
		else
		{
			log_debug('Cached file doesn\'t exist');
			$this->cache_exists = FALSE;
			return FALSE;
		}
	}
	
	private function writeCache($contents)
	{
		$this->getFileName();
		$file = $this->filename;
		
		$contents = '<!-- cache:' . $this->cache_page . ' -->' . $contents;
		
		file_put_contents($file, $contents);
		log_debug('Cached file saved');
	}
	
}

// END of ZigPHP Cache Class
// Filename: cache.php 
// Location: root/system/