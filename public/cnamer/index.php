<?php

require_once 'vendor/autoload.php';

$request_url = $_REQUEST['uri'];

$url	= new Cnamer\Url($request_url);
$dns	= new Cnamer\Dns;
$record	= new Cnamer\Record($dns);
$target	= new Cnamer\Target($record, 'cnamer.com', '176.58.124.239');
$redirect = new Cnamer\Redirect($url);

$options = $target->resolve($url->getHost());
$finale = $redirect->compile($options);

Header('location: ' . $finale['destination'], true, $finale['statuscode']);
