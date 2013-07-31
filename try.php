<?php

require_once __DIR__ . '/bootstrap.php';

$request = array(
    "domain" => $argv[1],
    "uri" => (isset($argv[2]) ? $argv[2] : false),
);

$cnamer = new Cnamer\Cnamer();
$redirect = $cnamer->redirect($request);

print_r($redirect);