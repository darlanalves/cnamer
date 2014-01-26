<?php namespace Cnamer;

use Memcached;

/**
 * @uses CacheInterface
 */
class MemcachedCache implements CacheInterface {
	
	/**
	 * Memcached object
	 * 
	 * @var Memcached
	 * @access protected
	 */
	protected $memcached;
	
	/**
	 * Constructor
	 * 
	 * Create a new Memcached class with the Memcached object injected
	 * 
	 * @param Memcached $memcached
	 * @access public
	 */
	public function __construct(Memcached $memcached)
	{
		$this->memcached = $memcached;
	}
	
	/**
	 * Set $key in cache with $data for $seconds
	 * 
	 * @param string $key
	 * @param mixed $data
	 * @param int $seconds
	 * @access public
	 * @return boolean
	 */
	public function set($key, $data, $seconds)
	{
		return $this->memcached->set($key, $data, $seconds);
	}
	
	/**
	 * Check if $key exists in the cache
	 * 
	 * @param string $key
	 * @access public
	 * @return boolean
	 */
	public function has($key)
	{
		if (!$this->get($key))
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get the data from the cache for $key
	 * 
	 * @param string $key
	 * @access public
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->memcached->get($key);
	}
	
	/**
	 * Delete the key from the cache
	 * 
	 * @param string $key
	 * @access public
	 * @return boolean
	 */
	public function delete($key)
	{
		return $this->memcached->delete($key);
	}
	
}