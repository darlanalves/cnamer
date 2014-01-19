<?php

require 'vendor/autoload.php';

$dns		= new Cnamer\Dns;
$request	= new Cnamer\Request;
$cnamer		= new Cnamer\Cnamer($dns, 'cnamer.com');
$options	= new Cnamer\Options;
$parameters = new Cnamer\Parameters;

$request->parseUrl($argv[1]);
$target = $request->getHost();

if (!$cnamer->isHostSubdomainOfDomain($target))
{
	try {
		$target = $cnamer->resolveTarget($target);
	} catch (Exception $ex) {
		die('Error!!');
	}
}

$target_options = $options->convertRawOptionsToArray($target);

print_r($target_options);

$available_parameters = array(
	'scheme' => array(
		'value' => 'http'
	),
	'host' => array(
		
	),
	'path' => array(
		
	),
	'query' => array(
		
	),
	'statuscode' => array(
		'value' => 301
	),
	'uri' => array(
		
	),
	'uristring' => array(
		'join' => '/'
	)
);

$params = $parameters->createRedirectParametersArray($target_options, $available_parameters);
$parameters->setParameters($params);

print_r($params);

$url =	"{$parameters->getParameter('scheme')}://"
	.	"{$parameters->getParameter('host')}"
	.	"/{$parameters->getParameter('uristring')}";
	
echo $url;