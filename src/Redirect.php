<?php namespace Cnamer;

use Exception;

class Redirect {
	
	/**
	 * Constructor
	 * 
	 * @param \Cnamer\Url $url
	 */
	public function __construct(Url $url)
	{
		$this->url = $url;
	}
	
	/**
	 * Compile the redirect URL
	 * 
	 * @param string $options_string
	 * @access public
	 * @return array
	 */
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
	
	/**
	 * Make destination from array of options
	 * 
	 * @param array $options
	 * @throws Exception
	 * @access public
	 * @return string
	 */
	public function makeDestination($options)
	{
		if (!isset($options['host']))
		{
			throw new Exception('Host required to make destination');
		}
		
		$destination = isset($options['protocol']) ? $options['protocol'] : 'http';
		
		return $destination . '://' . $options['host'];
	}
	
	/**
	 * Turn options string (json, base64 encoded json or key value string) into 
	 * an array
	 * 
	 * @param string $options_string
	 * @access public
	 * @return array
	 */
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
	
	/**
	 * Render json string as array
	 * 
	 * @param string $string
	 * @access public
	 * @return array
	 */
	public function renderJson($string)
	{
		return json_decode($string, true);
	}
	
	/**
	 * Render string of key value pairs as an array
	 * 
	 * @param string $string
	 * @access public
	 * @return array
	 */
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