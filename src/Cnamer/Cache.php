<?php namespace Cnamer;

class Cache {
	
	protected $directory;
	
	protected $expiry;
	
	public function __construct($config)
	{
		$this->directory = $config['directory'];
		$this->expiry = $config['expiry'];
	}
	
	/**
	 * Check if the key is fresh: exists in the cache and has not expired
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function isCacheFresh($key) 
	{
		$cache_file = $this->directory . '/' . md5($key) . '.json';
		
		if (file_exists($cache_file) && filemtime($cache_file) >= time() - $this->expiry)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Retrieve the contents of the cache
	 * 
	 * @param type $key
	 * @return type
	 */
	public function getCachedData($key)
	{ 
		$cache_file = $this->directory . '/' . md5($key) . '.json';
		
		return json_decode(file_get_contents($cache_file));
	}
	
	/**
	 * Store the data in the cache
	 * 
	 * @param type $key
	 * @param type $data
	 */
	public function storeData($key, $data) 
	{
		$cache_file = $this->directory . '/' . md5($key) . '.json';
		$cache_data = json_encode($data);
		
		file_put_contents($cache_file, $cache_data);
	}
	
	/**
	 * Empty the cache
	 */
	public function emptyCache()
	{
		$files = glob($this->directory . '/*');
		foreach ($files as $file)
		{
			unlink($file);
		}
	}
	
}