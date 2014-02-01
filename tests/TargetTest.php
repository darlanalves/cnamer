<?php

class TargetTest extends PHPUnit_Framework_TestCase {
	
	public function testHostIsUnderDomain()
	{
		$record = $this->getMock('Cnamer\Record');
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertTrue($target->isHostUnderDomain('example.com.cnamer.com'));
	}
	
	public function testHostIsNotUnderDomain()
	{
		$record = $this->getMock('Cnamer\Record');
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertFalse($target->isHostUnderDomain('example.com'));
	}
	
	public function testRemoveDomainFromTarget()
	{
		$record = $this->getMock('Cnamer\Record');
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('example.com', $target->removeDomainFromTarget('example.com.cnamer.com'));
	}
	
	public function testResolveHostnameOfCnamerConfig()
	{
		$record = $this->getMock('Cnamer\Record');
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('example.com', $target->resolve('example.com.cnamer.com'));
	}
	
	public function testResolveHostnameOfCnameConfig()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->any())->method('getCname')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'CNAME', 'target' => 'example.com.cnamer.com']
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('example.com', $target->resolve('example.example.com'));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testResolveHostnameOfCnameNotCnamerDomain()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->any())->method('getCname')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'CNAME', 'target' => 'example.com.example.org']
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('example.com', $target->resolve('example.example.com'));
	}
	
	public function testResolveHostnameOfTxtConfig()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->any())->method('getCname')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'CNAME', 'target' => 'txt.cnamer.com']
		));
		$record->expects($this->any())->method('getTxt')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'TXT', 'txt' => '{"destination":"http://example.com/", "statuscode": 301}']
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('{"destination":"http://example.com/", "statuscode": 301}', $target->resolve('example.example.com'));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testResolveHostnameOfTxtConfigWithMissingOptionsRecord()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->any())->method('getCname')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'CNAME', 'target' => 'txt.cnamer.com']
		));
		$record->expects($this->any())->method('getTxt')->will($this->returnValue(
			false
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('{"destination":"http://example.com/", "statuscode": 301}', $target->resolve('example.example.com'));
	}
	
	public function testResolveHostnameOfAConfig()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->at(0))->method('getCname')->will($this->returnValue(
			false
		));
		
		$record->expects($this->at(2))->method('getCname')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'CNAME', 'target' => 'example.com.cnamer.com']
		));
		
		$record->expects($this->any())->method('getA')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'A', 'ip' => '127.0.0.1']
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$this->assertEquals('example.com', $target->resolve('example.com'));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testResolveHostnameOfANotPointingToCnamer()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->at(0))->method('getCname')->will($this->returnValue(
			false
		));
		
		$record->expects($this->any())->method('getA')->will($this->returnValue(
			['host' => 'example.example.com', 'class' => 'IN', 'ttl' => 600, 'type' => 'A', 'ip' => '127.0.0.2']
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$target->resolve('example.com');
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testResolveHostnameWithNoConfig()
	{
		$record = $this->getMock('Cnamer\Record');
		$record->expects($this->any())->method('getCname')->will($this->returnValue(
			false
		));
		
		$record->expects($this->any())->method('getA')->will($this->returnValue(
			false
		));
		
		$record->expects($this->any())->method('getTxt')->will($this->returnValue(
			false
		));
		
		$target = new Cnamer\Target($record, 'cnamer.com', '127.0.0.1');
		$target->resolve('example.com');
	}

}