<?php

class DnsTest extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$cache_config = array(
			'directory' => __DIR__ . '/../data/cache',
			'expiry' => '3600'
		);
		
		$this->cache = new Cnamer\Cache($cache_config);
		$this->dns = new Cnamer\Dns($this->cache);
	}
	
	public function testCnameRecordLookup()
	{
		$record = $this->dns->lookupCnameRecord('github.cnamer.org');
		print_r($record);
	}
	
	public function testTxtRecordLookup()
	{
		$record = $this->dns->lookupCnameRecord('github.cnamer.org');
		print_r($record);
	}
	
	public function tearDown()
	{
		$this->cache->emptyCache();
	}
	
}