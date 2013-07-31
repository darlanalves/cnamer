<?php

require_once __DIR__ . '/bootstrap.php';

$request = array(
    "domain" => $argv[1],
    "uri" => "",
);

$cnamer = new Cnamer\Cnamer();
$redirect = $cnamer->redirect($request);

print_r($redirect);