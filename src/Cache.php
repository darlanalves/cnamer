<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT	
 */

namespace Cnamer;

use Exception;

class Cache {
	
	/**
	 * Cache directory location
	 * 
	 * @var string
	 * @access protected
	 */
	protected $directory;
	
	/**
	 * Cache expiry time
	 * 
	 * @var string 
	 * @access protected
	 */
	protected $expiry;
	
	/**
	 * Cache file
	 * 
	 * @var string
	 */
	protected $file;
	
	/**
	 * Constructor
	 * 
	 * @param string $directory		Cache directory
	 * @param int $expiry			Cache expiry time
	 */
	public function __construct($directory, $expiry)
	{
		$this->directory = $directory;
		$this->expiry = $expiry;
	}
	
	/**
	 * Set directory path
	 * 
	 * @param string $directory
	 */
	public function setDirectory($directory)
	{
		$this->directory = $directory;
	}
	
	/**
	 * Get directory path
	 * 
	 * @return string
	 */
	public function getDirectory()
	{
		return $this->directory;
	}
	
	/**
	 * Get cached data for the specified key
	 * 
	 * @param string $key
	 * @throws Exception
	 * @access public
	 * @return string
	 */
	public function get($key)
	{
		$file = $this->compileFilePath($key);
		
		if (!file_exists($file))
		{
			throw new Exception('Cache data cannot be found');
		}
		
		return file_get_contents($file);
	}
	
	/**
	 * Store data in cache
	 * 
	 * @param type $key
	 * @param type $data
	 * @access public
	 * @return void
	 */
	public function store($key, $data)
	{
		$file = $this->compileFilePath($key);
		
		file_put_contents($file, $data);
	}
	
	/**
	 * Check if the cache is fresh
	 * 
	 * @param string $key
	 * @access public
	 * @return boolean
	 */
	public function isFresh($key)
	{
		$file = $this->compileFilePath($key);
		
		if (!file_exists($file))
		{
			return false;
		}
		
		if( filemtime($file) < time() - $this->expiry)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Take key and turn it into a file safe key and return the path
	 * 
	 * @param string $key
	 * @access public
	 * @return string
	 */
	public function compileFilePath($key)
	{
		$filepath = $this->directory . '/' . $this->fileSafeString($key) . '.json';
		return $filepath;
	}
	
	/**
	 * Return file safe string
	 * 
	 * @param type $string
	 * @access public
	 * @return string
	 */
	public function fileSafeString($string)
	{
		$replace = array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/');
		$with = array('_', '.', '');
		return preg_replace($replace, $with, $string);
	}
	
}