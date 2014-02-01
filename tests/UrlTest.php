<?php

class UrlTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @expectedException Exception
	 */
	public function testInvalidUrlThrowsException()
	{
		$url = new Cnamer\Url('www.google.com');
	}
	
	public function testValidUrlParses()
	{
		$url = new Cnamer\Url('https://www.google.com/search?q=CNAMER#fragment');
		
		$this->assertEquals('https', $url->getScheme());
		$this->assertEquals('www.google.com', $url->getHost());
		$this->assertEquals('search', $url->getPath());
		$this->assertEquals('q=CNAMER', $url->getQuery());
		$this->assertEquals('fragment', $url->getFragment());
		
		$this->assertEquals('/search', $url->getTruePath());
	}
	
	public function testEmptyComponentsReturnNull()
	{
		$url = new Cnamer\Url('https://www.google.com');
		$this->assertNull($url->getPath());
		$this->assertNull($url->getQuery());
		$this->assertNull($url->getFragment());
	}
	
	public function testBuildRequestString()
	{
		$url = new Cnamer\Url('http://example.com/hello-world?key=val#fragment');
		$this->assertEquals('/hello-world?key=val#fragment', $url->buildRequestString(true));
		$this->assertEquals('http://example.com/hello-world?key=val#fragment', $url->buildRequestString());
	}
	
}