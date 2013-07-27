<?php

require_once __DIR__ . '/../bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$request = array(
    "domain" => "uritruedev.appledave.co.uk",
    "uri" => "",
);

$cnamer = new Cnamer\Cnamer($request);

try {
    $redirect = $cnamer->redirect();
} catch (Exception $e) {
    error_log(json_encode(array("request" => $request, "error" => $e->getMessage())) . "\n", 3, CNAMER_DIR . 'logs/error.log');
    die(include("error.php"));
}


die('during dev we dont want favicon request');
# Header("HTTP/1.0 {$redirect['statuscode']}");
# Header("Location: {$redirect['destination']}");