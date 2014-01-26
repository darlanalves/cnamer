<?php 

class MemcachedCacheTest extends PHPUnit_Framework_TestCase {
	
	public function testGetFromCache()
	{
		$memcached = $this->getMock('Memcached');
		$memcached->expects($this->at(0))->method('get')->will($this->returnValue(['Hello World!']));
		$cache = new Cnamer\MemcachedCache($memcached);
		$this->assertEquals(['Hello World!'], $cache->get('testcase_key'));
	}
	
	public function testStoreInCache()
	{
		$memcached = $this->getMock('Memcached');
		$memcached->expects($this->at(0))->method('set')->will($this->returnValue(true));
		$cache = new Cnamer\MemcachedCache($memcached);
		$this->assertTrue($cache->set('testcase_key', ['Hello World!'], 3600));
	}
	
	public function testKeyExistsInCache()
	{
		$memcached = $this->getMock('Memcached');
		$memcached->expects($this->at(0))->method('get')->will($this->returnValue(['Hello World!']));
		$cache = new Cnamer\MemcachedCache($memcached);
		$this->assertTrue($cache->has('testcase_key'));
	}
	
	public function testKeyDoesntExistInCache()
	{
		$memcached = $this->getMock('Memcached');
		$memcached->expects($this->at(0))->method('get')->will($this->returnValue(false));
		$cache = new Cnamer\MemcachedCache($memcached);
		$this->assertFalse($cache->has('testcase_key'));
	}
	
	public function testDeleteKeyFromCache()
	{
		$memcached = $this->getMock('Memcached');
		$memcached->expects($this->at(0))->method('delete')->will($this->returnValue(true));
		$cache = new Cnamer\MemcachedCache($memcached);
		$this->assertTrue($cache->delete('testcase_key'));
	}
	
}