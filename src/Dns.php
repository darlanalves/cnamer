<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT
 */

namespace Cnamer;

use Exception;

class Dns {
	
	public function setDomain($domain)
	{
		$this->domain = $domain;
	}
	
	public function resolveHost()
	{
		if ($record = $this->lookupCnameRecord($host))
		{
			return $record;
		}
	}
	
	public function lookupAnyRecord($host)
	{
		return $this->performRecordLookup($host, DNS_ALL);
	}
	
	public function lookupARecord($host)
	{
		return $this->performRecordLookup($host, DNS_A);
	}
	
	public function lookupCnameRecord($host)
	{
		return $this->performRecordLookup($host, DNS_CNAME);
	}
	
	public function lookupTxtRecord($host)
	{
		return $this->performRecordLookup($host, DNS_TXT);
	}
	
	public function performRecordLookup($host, $type, $cache = true)
	{
		$cache_key = $host . '_' . $type;
		
		if (apc_fetch($cache_key) && $cache)
		{
			return apc_fetch($cache_key);
		}
		
		$dns_records = dns_get_record($host, $type);
		
		if (!$dns_records)
		{
			return false;
		}
		
		$record = $dns_records[0];
		
		if ($record)
		{
			apc_add($cache_key, $record, 3600);
		}
		
		return $record;
	}
	
}