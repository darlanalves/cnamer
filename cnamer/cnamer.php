<?php

require_once __DIR__ . '/../bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$cnamer = new Cnamer\Cnamer();

try {
    $redirect = $cnamer->redirect($request);
} catch (Exception $e) {
    error_log(json_encode(array("request" => $request, "error" => $e->getMessage())) . "\n", 3, CNAMER_DIR . 'logs/error.log');
    $destination = 'http://'. CNAMER_DOMAIN . '/error.php?request=' . $request['domain'] . '&uri=' . $request['uri'];
    
    Header("HTTP/1.0 302");
    Header("Location: {$destination}");
}

Header("HTTP/1.0 302");
Header("Location: {$redirect['destination']}");