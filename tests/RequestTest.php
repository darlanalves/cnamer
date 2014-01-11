<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT	
 */

class RequestTest extends PHPUnit_Framework_TestCase {
	
	public function testUrlHostIsGoogle()
	{
		$request = new Cnamer\Request('cnamer.com');
		$components = $request->parseUrl('http://google.com');
		
		$this->assertEquals('google.com', $components['host']);
	}
	
	public function testUrlTypeIsCnamer()
	{
		$request = new Cnamer\Request('cnamer.com');
		$components = $request->parseUrl('http://google.com.cnamer.com');
		
		$this->assertTrue($components['cnamer']);
	}
	
	public function testUrlTypeIsNotCnamer()
	{
		$request = new Cnamer\Request('cnamer.com');
		$components = $request->parseUrl('http://google.com');
		
		$this->assertFalse($components['cnamer']);
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testMalformedDomainThrowsException()
	{
		$request = new Cnamer\Request('cnamer.com');
		$request->parseUrl('http:cnamer.com');
	}
	
}