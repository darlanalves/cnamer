<?php namespace Cnamer;

use Exception;

/**
 * @uses DnsInterface
 */
class Dns implements DnsInterface {
	
	/**
	 * Get the first record of $type for $hostname
	 * 
	 * @param string $hostname
	 * @param int $type
	 * @access public
	 * @return string|null
	 */
	public function get_record($hostname, $type)
	{
		$dns_records = dns_get_record($hostname, $type);
		
		if (empty($dns_records))
		{
			return null;
		}

		return array_values($dns_records)[0];
	}
}