<?php

class RequestTest extends PHPUnit_Framework_TestCase {
	
	public function testRequestParseReturnsProperties()
	{
		$request = new Cnamer\Request;
		$request->setDomain('cnamer.com');
		$url = $request->parseUrl('http://example.org');
		
		$expected = array(
			'scheme' => 'http',
			'host' => 'example.org',
			'type' => 'domain'
		);
		
		$this->assertEquals($expected, $url);
	}
	
	public function testRequestHostTypeIsDomain()
	{
		$request = new Cnamer\Request;
		$request->setDomain('cnamer.com');
		
		$this->assertEquals('domain', $request->hostType('example.org'));
	}
	
	public function testRequestHostTypeIsCnamer()
	{
		$request = new Cnamer\Request;
		$request->setDomain('cnamer.com');
		
		$this->assertEquals('cnamer', $request->hostType('example.org.cnamer.com'));
	}
	
}