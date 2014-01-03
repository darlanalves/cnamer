<?php namespace Cnamer;

use Exception;

class Dns {
	
	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}
	
	/**
	 * 
	 * @param string $cname
	 */
	public function lookupCnameRecord($host) 
	{ 
		return $this->dnsLookup($host, DNS_CNAME);
	}
	
	/**
	 * 
	 * @param string $txt
	 */
	public function lookupTxtRecord($host) 
	{ 
		return $this->dnsLookup($host, DNS_TXT);
	}
	
	/**
	 * DNS Lookup, from cache or fresh
	 * 
	 * @param string $host
	 * @param string $type
	 */
	public function dnsLookup($host, $type)
	{
		if ($this->cache->isCacheFresh($host))
		{
			return $this->cache->getCachedData($host);
		}
		
		$dns_data = $this->performDnsLookup($host, $type);
		
		$this->cache->storeData($host, $dns_data);
		
		return $dns_data;
	}
	
	/**
	 * Perform a fresh DNS Lookup
	 * 
	 * @param string $host
	 * @param string $type
	 */
	public function performDnsLookup($host, $type)
	{
		$record = dns_get_record($host, $type);
		
		if (!empty($record))
		{
			return $record[0];
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param string $host
	 * @param array $host
	 */
	public function storeRecordResponse($host, $response) { }
	
	/**
	 * 
	 * @param string $host
	 */
	public function retrieveRecordResponse($host) { }
	
}