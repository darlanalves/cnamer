<?php namespace Cnamer;

use Exception;

class Target {
	
	/**
	 * Record object
	 * 
	 * @var RecordInterface
	 * @access protected
	 */
	protected $record;
	
	/**
	 * Domain of the cnamer install
	 * 
	 * @var string
	 * @access protected
	 */
	protected $domain;
	
	/**
	 * IP of the cnamer install
	 * 
	 * @var string
	 * @access protected
	 */
	protected $ip;
	
	/**
	 * Constructor
	 * 
	 * @param \Cnamer\RecordInterface $record
	 * @param string $domain
	 * @param string $ip
	 * @access public
	 */
	public function __construct(RecordInterface $record, $domain, $ip)
	{
		$this->record = $record;
		$this->domain = $domain;
		$this->ip = $ip;
	}
	
	/**
	 * Resolve the hostnames target, following all records to the cnamer target
	 * 
	 * @param string $hostname
	 * @throws Exception
	 * @access public
	 * @return string
	 */
	public function resolve($hostname)
	{
		/*
		 * If the hostname is under the cnamer domain (eg: google.com.cnamer.com
		 * we don't need to perform any lookup, we can just return this as the
		 * target
		 */
		if ($this->isHostUnderDomain($hostname, $this->domain))
		{
			return $this->removeDomainFromTarget($hostname);
		}
		
		/*
		 * Try and resolve the hostname as a cname
		 */
		if ($cname = $this->resolveCname($hostname))
		{
			return $cname;
		}
		
		/*
		 * Try and resolve the hostname as an A
		 */
		if ($a = $this->resolveA($hostname))
		{
			return $a;
		}
		
		throw new Exception('A valid CNAMER target cannot be found');
	}
	
	/**
	 * Resolve the cname record target
	 * 
	 * @param string $hostname
	 * @throws Exception
	 * @access public
	 * @return string|boolean
	 */
	public function resolveCname($hostname)
	{
		if (!$cname = $this->record->getCname($hostname))
		{
			return false;
		}

		if ($cname['target'] == "txt.{$this->domain}")
		{
			if($txt = $this->record->getTxt("options.{$hostname}"))
			{
				return $txt['txt'];
			}
			
			throw new Exception("TXT Record not found for options.{$hostname}");
		}

		if (!$this->isHostUnderDomain($cname['target']))
		{
			return false;
		}
		
		return $this->removeDomainFromTarget($cname['target']);
	}
	
	/**
	 * Resolve the A record target
	 * 
	 * @param string $hostname
	 * @throws Exception
	 * @access public
	 * @return string|boolean
	 */
	public function resolveA($hostname)
	{
		if (!$a = $this->record->getA($hostname))
		{
			return false;
		}
		
		if ($a['ip'] != $this->ip)
		{
			return false;
		}
		
		return $this->resolveCname("cnamer.{$hostname}");
	}
	
	/**
	 * Check if $hostname is under the cnamer domain, for example 
	 * "example.com.cnamer.com" is under "cnamer.com", "example.com" is not.
	 * 
	 * @param string $hostname
	 * @param string $domain
	 * @access public
	 * @return boolean
	 */
	public function isHostUnderDomain($hostname, $domain = null)
	{
		$domain = $domain ?: $this->domain;
		
		if (substr($hostname, -strlen($domain), strlen($domain)) == $domain)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Remove the cnamer domain from the target, for example 
	 * "example.com.cnamer.com" becomes "example.com"
	 * 
	 * @param string $target
	 * @param string $domain
	 * @access public
	 * @return string
	 */
	public function removeDomainFromTarget($target, $domain = null)
	{
		$domain = $domain ?: $this->domain;
		return substr($target, 0, -(strlen($domain) + 1));
	}
	
}