<?php 

class RecordTest extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$dns = $this->getMock('Cnamer\Dns');
		$dns->expects($this->any())->method('get_record')->will($this->returnValue(['host' => 'google.com']));
		$this->record = new Cnamer\Record($dns);
	}
	
	public function testGetARecord()
	{
		$record = $this->record->getA('google.com');
		$this->assertContains('google.com', $record);
	}
	
	public function testGetCnameRecord()
	{
		$record = $this->record->getCname('google.com');
		$this->assertContains('google.com', $record);
	}
	
	public function testGetTxtRecord()
	{
		$record = $this->record->getTxt('google.com');
		$this->assertContains('google.com', $record);
	}
	
}