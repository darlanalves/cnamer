<?php 

class RedirectTest extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$url = $this->getMockBuilder('Cnamer\Url')->disableOriginalConstructor()->getMock();
		$url->expects($this->any())->method('buildRequestString')->will($this->returnValue('/hello-world?name=citricsquid#fragment'));
		$this->redirect = new Cnamer\Redirect($url);
	}
	
	public function testRenderKeyVal()
	{
		$options = $this->redirect->renderKeyVal('example.com');
		$this->assertEquals(['host' => 'example.com'], $options);
		
		$options = $this->redirect->renderKeyVal('example.com-opts-statuscode.301');
		$this->assertEquals(['host' => 'example.com', 'statuscode' => '301'], $options);
	}
	
	public function testRenderJson()
	{
		$json = $this->redirect->renderJson('{"key": "value"}');
		$this->assertEquals(['key' => 'value'], $json);
	}
	
	public function testOptionsAsArray()
	{
		$json = '{"destination": "http://example.com/", "statuscode": "301"}';
		$json_encoded = base64_encode($json);
		$keyval = 'example.com-opts-statuscode.301';
		
		$this->assertEquals(['destination' => 'http://example.com/', 'statuscode' => '301'], $this->redirect->optionsAsArray($json));
		$this->assertEquals(['destination' => 'http://example.com/', 'statuscode' => '301'], $this->redirect->optionsAsArray($json_encoded));
		$this->assertEquals(['host' => 'example.com', 'statuscode' => '301'], $this->redirect->optionsAsArray($keyval));
	}
	
	public function testMakeDestination()
	{
		$options = ['host' => 'example.com'];
		$this->assertEquals('http://example.com', $this->redirect->makeDestination($options));
	}
	
	/**
	 * @expectedException Exception
	 */
	public function testMakeDestinationWithoutHostThrowsException()
	{
		$this->redirect->makeDestination([]);
	}
	
	public function testCompile()
	{
		$json = $this->redirect->compile('{"destination": "http://example.com", "statuscode": "301"}');
		$this->assertEquals(['statuscode' => '301', 'destination' => 'http://example.com'], $json);
		
		$keyval = $this->redirect->compile('example.com');
		$this->assertEquals(['statuscode' => '301', 'destination' => 'http://example.com'], $keyval);
		
		$uri = $this->redirect->compile('example.com-opts-uri.true');
		$this->assertEquals(['statuscode' => '301', 'destination' => 'http://example.com/hello-world?name=citricsquid#fragment'], $uri);
	}
	
}