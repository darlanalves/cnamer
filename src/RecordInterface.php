<?php namespace Cnamer;

interface RecordInterface {
	
	/**
	 * Gets the CNAME record for $hostname
	 * 
	 * @param string $hostname
	 * @access public
	 * @return array|boolean
	 */
	public function getCname($hostname);
	
	/**
	 * Gets the TXT record for $hostname
	 * 
	 * @param string $hostname
	 * @access public
	 * @return array|boolean
	 */
	public function getTxt($hostname);
	
	/**
	 * Gets the A record for $hostname
	 * 
	 * @param string $hostname
	 * @access public
	 * @return array|boolean
	 */
	public function getA($hostname);
	
}
