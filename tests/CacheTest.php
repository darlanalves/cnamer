<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT	
 */

class CacheTest extends PHPUnit_Framework_TestCase {
	
	public function __construct()
	{
		$this->directory = __DIR__ . '/data';
	}
	
	public function testDataStoreIsSuccesful()
	{
		$cache = new Cnamer\Cache($this->directory, 3600);
		$cache->store('cnamer.com', '{key:"value"}');
		
		$cache_file = $cache->getDirectory() . '/cnamer.com.json';
		
		$this->assertTrue(file_exists($cache_file));
		$this->assertEquals('{key:"value"}', file_get_contents($cache_file));
	}
	
	public function testDataIsFresh()
	{
		$cache = new Cnamer\Cache($this->directory, 3600);
		$cache->store('cnamer.com', '{key:"value"}');
		
		$this->assertTrue($cache->isFresh('cnamer.com'));
	}
	
	public function testDataIsNotFresh()
	{
		$cache = new Cnamer\Cache($this->directory, 3600);
		$cache->store('cnamer.com', '{key:"value"}');
		
		$twohoursago = date('YmdHi', (time() - 7200));
		exec("touch -t " . $twohoursago . " " . $this->directory . "/cnamer.com.json");
		
		$this->assertFalse($cache->isFresh('cnamer.com'));
	}
	
	public function testStringIsFileSafe()
	{
		$cache = new Cnamer\Cache($this->directory, 3600);
		
		$this->assertEquals('abc', $cache->fileSafeString('Ìa^b^cÌ'));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testMissingCacheThrowsException()
	{
		$cache = new Cnamer\Cache($this->directory, 3600);
		$cache->get('cnamer.com');
	}
	
	public function tearDown()
	{
		array_map('unlink', glob($this->directory . '/*'));
	}
	
}