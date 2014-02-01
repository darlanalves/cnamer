<?php namespace Cnamer;

use Exception;

class Url {
	
	/**
	 * URL's scheme
	 * 
	 * @var string
	 * @access protected
	 */
	protected $scheme;
	
	/**
	 * URL's host
	 * 
	 * @var string
	 * @access protected
	 */
	protected $host;
	
	/**
	 * URL's path
	 * 
	 * @var string|null
	 * @access protected
	 */
	protected $path = null;
	
	/**
	 * URL's query string
	 * 
	 * @var string|null
	 * @access protected
	 */
	protected $query = null;
	
	/**
	 * URL's fragment
	 * 
	 * @var string|null
	 * @access protected
	 */
	protected $fragment = null;
	
	/**
	 * Constructor
	 * 
	 * Validate the specified URL and assign components, if component isn't part
	 * of the url then it will be set to null
	 * 
	 * @param string $url
	 * @throws Exception
	 */
	public function __construct($url)
	{
		if (filter_var($url, FILTER_VALIDATE_URL) === false)
		{
			throw new Exception('URL is not valid');
		}
		
		$this->scheme	= parse_url($url, PHP_URL_SCHEME);
		$this->host		= parse_url($url, PHP_URL_HOST);
		$this->path		= parse_url($url, PHP_URL_PATH);
		$this->query	= parse_url($url, PHP_URL_QUERY);
		$this->fragment	= parse_url($url, PHP_URL_FRAGMENT);
	}
	
	/**
	 * Get the URL's scheme
	 * 
	 * @access public
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}
	
	/**
	 * Get the URL's host
	 * 
	 * @access public
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}
	
	/**
	 * Get the URL's path (with / removed)
	 * 
	 * @access public
	 * @return string|null
	 */
	public function getPath()
	{
		if ($this->path)
		{
			return substr($this->path, 1);
		}
		
		return $this->path;
	}
	
	/**
	 * Get the URL's path (without any modification)
	 * 
	 * @access public
	 * @return string|null
	 */
	public function getTruePath()
	{
		return $this->path;
	}
	
	/**
	 * Get the URL's query string
	 * 
	 * @return string|null
	 */
	public function getQuery()
	{
		return $this->query;
	}
	
	/**
	 * Get the URL's fragment
	 * 
	 * @return string|null
	 */
	public function getFragment()
	{
		return $this->fragment;
	}
	
	public function buildRequestString($optional_only = false)
	{
		$string = '';
		
		if ($optional_only == false)
		{
			$string .= $this->getScheme();
			$string .= '://';
			$string .= $this->getHost();
		}
		
		$string .= $this->getTruePath();
		$string .= $this->getQuery() ? '?' . $this->getQuery() : '';
		$string .= $this->getFragment() ? '#' . $this->getFragment() : '';
		
		return $string;
	}
	
}