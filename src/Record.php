<?php namespace Cnamer;

/**
 * @uses RecordInterface
 */
class Record implements RecordInterface {
	
	/**
	 * Constructor
	 * 
	 * @param \Cnamer\DnsInterface $dns
	 */
	public function __construct(DnsInterface $dns = null)
	{
		$this->dns = $dns ?: new Dns;
	}
	
	/**
	 * Get the A record for the hostname
	 * 
	 * @param string $hostname
	 * @access public
	 * @return array|boolean
	 */
	public function getA($hostname)
	{
		return $this->dns->get_record($hostname, DNS_A);
	}

	/**
	 * Get the CNAME record for the hostname
	 * 
	 * @param string $hostname
	 * @access publix
	 * @return array|boolean
	 */
	public function getCname($hostname)
	{
		return $this->dns->get_record($hostname, DNS_CNAME);
	}

	/**
	 * Get the TXT record for the hostname
	 * 
	 * @param string $hostname
	 * @access public
	 * @return array|boolean
	 */
	public function getTxt($hostname)
	{
		return $this->dns->get_record($hostname, DNS_TXT);
	}

}