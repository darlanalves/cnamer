<?php namespace Cnamer;

use Exception;

class Request {
	
	/**
	 * The CNAMER domain
	 * 
	 * @var domain
	 * @access protected
	 */
	protected $domain;
	
	/**
	 * Set CNAMER's domain
	 * 
	 * @param string $domain
	 */
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}
	
	/**
	 * Parse a URL
	 * 
	 * @access public
	 * @return array
	 */
	public function parseUrl($url)
	{
		$url = strtolower($url);
		
		if (filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			throw new Exception('URL is not a valid URL');
		}
		
		$components = parse_url($url);
		$components['type'] = $this->hostType($components['host']);
		
		return $components;
	}
	
	/**
	 * Determine the type of URL (cnamer, or non-cnamer (domain))
	 * 
	 * @access public
	 * @return string $type
	 */
	public function hostType($host)
	{
		if ( substr($host, -strlen($this->domain), strlen($this->domain)) == $this->domain)
		{
			return 'cnamer';
		}
		
		return 'domain';
	}
	
}