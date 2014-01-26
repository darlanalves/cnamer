<?php namespace Cnamer;

interface CacheInterface {
	
	/**
	 * Adds the $data to the cache with the $key for $seconds
	 * 
	 * @param string $key
	 * @param mixed $data
	 * @param int $seconds
	 * @access public
	 * @return boolean
	 */
	public function set($key, $data, $seconds);
	
	/**
	 * Gets the data for the $key
	 * 
	 * @param string $key
	 * @access public
	 * @return mixed
	 */
	public function get($key);
	
	/**
	 * Check if the key exists in the cache
	 * 
	 * @param string $key
	 * @access public
	 * @return boolean
	 */
	public function has($key);
	
	
	/**
	 * Delete the data for the key
	 * 
	 * @param string $key
	 * @access public
	 * @return boolean
	 */
	public function delete($key);
	
}