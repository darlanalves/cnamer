<?php
/**
 * @author		Samuel Ryan <sam@samryan.co.uk>
 * @copyright	(c) Samuel Ryan
 * @link		https://github.com/citricsquid/cnamer
 * @license		MIT
 */

namespace Cnamer;

use Exception;

class Parameters {
	
	public function createRedirectParametersArray($input_options, $available_options)
	{
		$parameters = $this->makeDefaultParameters($available_options);
		$options = $this->removeInvalidOptions($input_options, $available_options);

		foreach ($options as $option)
		{
			$key = $option['key'];
			
			if (isset($available_options[$key]['join']))
			{
				$option['value'] = $this->joinValueToOption($parameters[$key], $available_options[$key]['join'], $option['value']);
			}
			
			$parameters[$key] = $option['value'];
		}
		
		return $parameters;
	}
	
	public function makeDefaultParameters($options)
	{
		$parameters = array();
		
		foreach ($options as $key => $option)
		{
			$parameters[$key] = isset($option['value']) ? $option['value'] : '';
		}
		
		return $parameters;
	}
	
	public function removeInvalidOptions($input_options, $valid_options)
	{
		foreach ($input_options as $key => $option)
		{
			if (!isset($valid_options[$option['key']]))
			{
				unset($input_options[$key]);
			}
		}
		
		return $input_options;
	}
	
	public function joinValueToOption($parameter_value, $glue, $option_value)
	{
		if (!empty($parameter_value))
		{
			return $parameter_value . $glue . $option_value;
		}
		
		return $option_value;
	}
	
	public function setParameters($parameters)
	{
		foreach ($parameters as $parameter => $value)
		{
			$this->$parameter = $value;
		}
	}
	
	public function getParameter($parameter)
	{
		if (isset($this->$parameter) && !empty($this->$parameter))
		{
			return $this->$parameter;
		}
		
		return false;
	}
	
	public function getAffixedParameter($parameter, $prefix = false, $suffix = false)
	{
		$parameter = $this->getParameter($parameter);
		
		if (!$parameter)
		{
			return false;
		}
		
		return $prefix . $parameter . $suffix;
	}
	
}