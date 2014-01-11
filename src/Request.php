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
	 * The CNAMER domain
	 * 
	 * @var string
	 * @access protected
	 */
	protected $domain;
	
	/**
	 * Constructor
	 * 
	 * @param string $domain	The CNAMER domain
	 * @access public
	 */
	public function __construct($domain)
	{
		$this->domain = $domain;
	}
	
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
		
		$url_components = parse_url($url);
		$url_components['cnamer'] = $this->isHostCnamer($url_components['host']);
		
		return $url_components;
	}
	
	/**
	 * Checks if the domain is .$domain
	 * 
	 * @param string $host
	 * @access public
	 * @return boolean
	 */
	public function isHostCnamer($host)
	{
		if (substr($host, -strlen($this->domain), strlen($this->domain)) == $this->domain)
		{
			return true;
		}
		
		return false;
	}
	
}