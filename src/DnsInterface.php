<?php namespace Cnamer;

interface DnsInterface {
	
	/**
	 * Gets the record for $hostname of $type
	 * 
	 * @param string $hostname
	 * @param int $type
	 * @access public
	 * @return array|boolean
	 */
	public function get_record($hostname, $type);
	
}