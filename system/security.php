<?php

/*
| ======================================================================
| ZigPHP Security Class
| ======================================================================
|
| This class will filter and sanitize POST data
|
| Note: At the moment this is a very basic sanitation class for POST data.
|
*/

class security 
{
	
	private $zig;
	private $no_post_filter = FALSE;

	public function __construct()
	{
		$this->zig =& get_instance();
		$this->setPost($_POST);
		$this->setGet($_GET);
	}
	
	public function disable_post_filter($yes=1)
	{
		$this->no_post_filter = ($yes == 1) ? TRUE : FALS;
	}
	
	public function filter_post_data()
	{
		if ($this->no_post_filter == TRUE) 
		{
			return;
		}
		$this->zig->post = $this->filterData($this->zig->post);
		if (($this->zig->post))
		{
			log_debug('POST Data sanitized');
		}
	}
	
	private function setPost($post_data)
	{
		if (empty($post_data))
		{
			$this->zig->post = NULL;
			return;
		}
		$post = array();
		foreach ($post_data as $key => $value) 
		{		
			$post[$key] = $value;
		} 
		$post = array_to_object($post);
		
		$this->zig->post = $post;
		log_debug('POST data set');
	}
	
	private function setGet($get_data)
	{
		if (empty($get_data))
		{
			$this->zig->get = NULL;
			return;
		}
		$get = array();
		foreach ($get_data as $key => $value) 
		{		
			$get[$key] = $value;
		} 
		$get = array_to_object($get);
		
		$this->zig->get = $get;
		log_debug('GET data set');
	}
	
	public function filterData($data)
	{
		if (is_array($data)) 
		{
			$clean_data = array();
			foreach ($data as $key => $value) 
			{
				if ($key != '')
				{
					$clean_data[$key] = $this->filterData($value);
				}
				if (!is_array($value))
				{
					$clean_string = htmlentities(strip_tags($value));
					$clean_data[$key] = trim($clean_string);
				}
			}  
			return $clean_data;
		}
		elseif (is_object($data))
		{
			$clean_data = new stdClass();
			foreach ($data as $name => $value) 
			{
				if (isset($name))
				{
					$name = (trim($name));
					if ($name != '')
					{
						$clean_data->$name = $this->filterData($value);
					}
					if (!is_object($value))
					{
						$clean_string = htmlentities(strip_tags($value));
						$clean_data->$name = trim($clean_string);
					}	
				}
			}
			return $clean_data;
		}
	}	

}

// END of ZigPHP Security Class
// Filename: security.php 
// Location: root/system/