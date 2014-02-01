<?php

class DnsTest extends PHPUnit_Framework_TestCase {
	
	public function testGetRecord()
	{
		$dns = new Cnamer\Dns;
		$record = $dns->get_record('google.com', DNS_A);
		$this->assertContains('google.com', $record);
	}
	
	public function testGetRecordOnNonExistantHost()
	{
		$dns = new Cnamer\Dns;
		$record = $dns->get_record('thishostcannotexistbecausethehostisoverthelengthlimitfordomains.com', DNS_A);
		$this->assertNull($record);
	}
	
}