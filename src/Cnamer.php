<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT
 */

namespace Cnamer;

use Exception;

class Cnamer {
	
	protected $dns;
	
	protected $domain;
	
	public function __construct(Dns $dns, $domain)
	{
		$this->dns = $dns;
		$this->domain = $domain;
	}
	
	public function resolveTarget($host)
	{
		/**
		 * The majority of requests will be cnames, so we'll first look for a
		 * cname record for the domain
		 */
		if ($record = $this->dns->lookupCnameRecord($host))
		{
			if ($target = $this->resolveDeepTarget($host, $record))
			{
				return $target;
			}
		}
		
		/**
		 * If there isn't a cname for the domain, perhaps it's an A record, if it
		 * is an A record then there could be a cname for cnamer.domain defining
		 * the destination:
		 */
		if ($record = $this->dns->lookupCnameRecord("cnamer.{$host}"))
		{
			return $this->returnTargetIfSubdomain($record['target']);
		}
		
		/**
		 * A continuation of above, if there isn't a cnamer.domain cname record
		 * it's possible that they might be using the legacy method of the domain
		 * as a subdomain of the domain, eg: example.org.example.org:
		 */
		if ($record = $this->dns->lookupCnameRecord("{$host}.{$host}"))
		{
			return $this->returnTargetIfSubdomain($record['target']);
		}
		
		throw new Exception('No destination DNS record could be found');
	}
	
	public function resolveDeepTarget($host, $record)
	{
		$target = $record['target'];

		/**
		 * If the target is "txt.cnamer.com" then we need to do a look up
		 * for the txt record with the values
		 */
		if ($target == "txt.{$this->domain}")
		{
			/**
			 * If the host is "subdomain.example.org" we need to look for a
			 * txt record with the name "cnamer-subdomain.example.org", the
			 * value of this record can either be json, or a base64 encoded
			 * string
			 */
			if ($record = $this->dns->lookupTxtRecord("cnamer-{$host}"))
			{
				return $record['entries'][0];
			}

			throw new Exception('Destination TXT record cannot be found');
		}

		/**
		 * If the cname is found and is a subdomain of the cnamer domain,
		 * hurrah, we've found a destination!
		 */
		if ($this->isHostSubdomainOfDomain($target))
		{
			return $target;
		}

		throw new Exception('Destination CNAME record cannot be found');
	}
	
	public function returnTargetIfSubdomain($target)
	{
		if ($this->isHostSubdomainOfDomain($target))
		{
			return substr($target, 0, -(strlen($this->domain) + 1));
		}

		return false;
	}
	
	public function isHostSubdomainOfDomain($host)
	{
		if (substr($host, -strlen($this->domain), strlen($this->domain)) == $this->domain)
		{
			return true;
		}

		return false;
	}
	
}