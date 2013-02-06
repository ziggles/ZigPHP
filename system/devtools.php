<?php

/*
| =========================================
| ZigPHP DevTools Class
| =========================================
|
| This class serves to assist in application development.
|
*/

Class devtools 
{
	
	private $zig;
	public $times = array();
	
	public function __construct()
	{
		$this->zig = get_instance();
	}
	
	public function markTime($name)
	{
		$time = microtime();
		$time_array = explode(" ", $time);
		$final_time = $time_array[1] + $time_array[0];
		$this->times[$name] = $final_time;
	}
	
	public function compareTimes($time1, $time2)
	{
		if (!array_key_exists($time1, $this->times) || !array_key_exists($time2, $this->times))
		{
			showError('Make sure the times you are trying to compare have been previously marked', 'compareTimes');
			return FALSE;
		}
		
		$time1 = $this->times[$time1];
		$time2 = $this->times[$time2];
		
		$final_time = $time2 - $time1;
		$final_time = round($final_time, 5);
		return $final_time;
	}	
	
	public function getMemUsage($return_as_number = 0)
	{
		$mem_usage = memory_get_usage();
		
		if ($return_as_number == 1)
		{
			return $mem_usage;
		}
		else
		{
			if ($mem_usage < 1024)
			{
				return $mem_usage . 'bytes';
			}
			elseif ($mem_usage < 1048576)
			{
				return round($mem_usage/1024, 2) . ' KB';
			}
			else
			{
				return round($mem_usage/1048576, 2) . ' MB';
			}
		}
	}
	
	public function showDevReport()
	{
		if ($this->zig->cache->cache_exists === TRUE)
		{
			$this->zig->template = new template($this->zig);
		}
		log_debug('Loading Dev Report');
		$this->zig->template->setError(0);
		$this->zig->template->hideLayout();
		
		$templates = $this->zig->template->template_buffer;
		
		if ($this->zig->db) 
		{
			$this->zig->template->set('db_queries', $this->zig->db->debug_info);
		}
		
		$this->zig->template->unsetPage();
		$this->zig->template->set('templates', $templates);
		$this->zig->template->set('mem_usage', $this->getMemUsage());
		$this->zig->template->set('render_time', $this->compareTimes('execution_start', 'execution_end'));
		$this->zig->template->render('zigPHP_dev_report');	
		$this->zig->template->renderPage('dt');
	}
	
}

// END of ZigPHP DevTools Class
// Filename: devtools.php 
// Location: root/system/