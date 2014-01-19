<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT	
 */

namespace Cnamer;

use Exception;

class Request {
	
	/**
	 * Parses the URL
	 * 
	 * @param string $url	The URL to parse
	 * @throws Exception	If the URL cannot be parsed
	 * @access public
	 * @return array
	 */
	public function parseUrl($url)
	{
		$url = strtolower($url);
		
		if (filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			throw new Exception('URL cannot be parsed');
		}
		
		$components = parse_url($url);
		
		$this->scheme	= $components['scheme'];
		$this->host		= $components['host'];
		$this->path		= isset($components['path']) ? $components['path'] : null;
		$this->query	= isset($components['query']) ? $components['query'] : null;
		$this->fragment = isset($components['fragment']) ? $components['fragment'] : null;
	}
	
	public function getScheme()
	{
		return $this->scheme;
	}
	
	public function getHost()
	{
		return $this->host;
	}
	
}