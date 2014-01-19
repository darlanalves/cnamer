<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT
 */

namespace Cnamer;

use Exception;

class Options {
	
	/**
	 * Take a string of options (either keyval, json or base64 encoded json)
	 * and turn it into an array of values
	 * 
	 * @param mixed $options_string
	 * @return array
	 */
	public function convertRawOptionsToArray($options_string)
	{
		if (substr($options_string, -1) == '=')
		{
			$options_string = base64_decode($options_string);
		}
		
		if (json_decode($options_string))
		{
			return $this->renderJson($options_string);
		}
		
		return $this->renderKeyVal($options_string);
	}
	
	public function renderJson($string)
	{
		return json_decode($string, true);
	}
	
	public function renderKeyVal($string)
	{
		$opts = explode('-opts-', $string);
		$options = [];
		
		if ($opts[1])
		{
			$params = explode('-', $opts[1]);
			foreach ($params as $param)
			{
				list($key, $value) = explode('.', $param);
				$options[] = ['key' => $key, 'value' => $value];
			}
		}
		
		$options[] = ['key' => 'host', 'value' => $opts[0]];
		
		return $options;
	}
	
}