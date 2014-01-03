<?php

class CacheTest extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$this->config = array(
			'directory' => __DIR__ . '/../data/cache',
			'expiry' => '3600'
		);
		
		$this->cache = new Cnamer\Cache($this->config);
	}
	
	public function testCacheStoresData()
	{
		$this->cache->storeData('my-cache-key', array('a' => 'b'));
		$this->assertEquals('{"a":"b"}', $this->cache->getCachedData('my-cache-key'));
	}
	
	public function testCacheDeterminesCacheIsFresh()
	{
		$this->cache->storeData('my-cache-key', array('a' => 'b'));
		$this->assertTrue($this->cache->isCacheFresh('my-cache-key'));
	}
	
	public function testCacheDeterminesCacheIsNotFresh()
	{
		$this->assertFalse($this->cache->isCacheFresh('not-cached-key'));
	}
	
	public function tearDown()
	{
		$this->cache->emptyCache();
	}
	
}