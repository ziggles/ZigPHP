<?php

/*
| ======================================================================
| ZigPHP HTML Helper
| ======================================================================
|
*/

function anchor($link, $text, $attributes = '')
{
	$output = '<a href="';
	
	$zig =& get_instance();
	
	$output .= $zig->base_url . $link;
	
	$output .= '"';
	
	if ($attributes != '')
	{
		if(!is_array($attributes))
		{
			return FALSE;
		}
		else
		{
			foreach ($attributes as $key => $value)
			{
				$output .= $key . '="' . $value . '" ';
			}
		}
	}
	
	$output .= '>' . $text . '</a>';
	
	return $output;
}

function anchor_link($link)
{
	$zig =& get_instance();
	
	$output = $zig->base_url . $link;
	
	return $output;
}

// END of ZigPHP HTML Helper
// Filename: html.helper.php 
// Location: root/helpers/