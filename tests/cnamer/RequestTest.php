<?php

class RequestTest extends PHPUnit_Framework_TestCase {
	
	public function testValidUrlParse()
	{
		$request = new Cnamer\Request;
		$components = $request->parseUrl("http://samryan.co.uk");
		
		$this->assertArrayHasKey('scheme', $components);
		$this->assertArrayHasKey('host', $components);
	}
	
	/**
	 * @expectedException EXCEPTION
	 */
	public function testInvalidUrlParse()
	{
		$request = new Cnamer\Request;
		$request->parseUrl("http:samryan.co.uk");
	}
}