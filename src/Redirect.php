<?php namespace Cnamer;

use Exception;

class Redirect {
	
	public function __construct(Url $url)
	{
		$this->url = $url;
	}
	
	public function compile($options_string)
	{
		$options = $this->optionsAsArray($options_string);
		$headers = array();
		
		if (!isset($options['destination']))
		{
			$headers['destination'] = $this->makeDestination($options);
		}
		else
		{
			$headers['destination'] = $options['destination'];
		}
		
		if (isset($options['uri']) && $options['uri'] == "true")
		{
			$headers['destination'] .= $this->url->buildRequestString(true);
		}
		
		$headers['statuscode'] = (isset($options['statuscode'])) ? $options['statuscode'] : 301;
		
		return $headers;
	}
	
	public function makeDestination($options)
	{
		if (!isset($options['host']))
		{
			throw new Exception('Host required to make destination');
		}
		
		$destination = isset($options['protocol']) ? $options['protocol'] : 'http';
		
		return $destination . '://' . $options['host'];
	}
	
	public function optionsAsArray($options_string)
	{
		if (substr($options_string, -1) == '=') 
		{
			return $this->renderJson(base64_decode($options_string));
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
		
		if (isset($opts[1]))
		{
			$params = explode('-', $opts[1]);
			foreach ($params as $param)
			{
				list($key, $value) = explode('.', $param);
				$options[$key] = $value;
			}
		}
		
		$options['host'] = $opts[0];
		
		return $options;
	}
	
}