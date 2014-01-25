<?php

/**
 * @author      Samuel Ryan <sam@samryan.co.uk>
 * @copyright   (c) Samuel Ryan
 * @link        https://github.com/citricsquid/cnamer
 * @license     MIT
 */

class UrlTest extends PHPUnit_Framework_TestCase {
	
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
	
	/**
	 * @expectedException Exception
	 */
	public function testInvalidUrlThrowsException()
	{
		$url = new Cnamer\Url('www.google.com');
	}
	
}