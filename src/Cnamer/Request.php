<?php namespace Cnamer;

class Request {
	
	public function parseUrl($request_url)
	{
		$request_url = strtolower($request_url);
		
		if (filter_var($request_url, FILTER_VALIDATE_URL) === false)
		{
			throw new \Exception("Request URL is not a valid URL");
		}
	
		return parse_url($request_url);
	}
	
}