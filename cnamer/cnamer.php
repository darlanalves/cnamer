<?php

require_once __DIR__ . '/../bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$cnamer = new Cnamer\Cnamer($request);

try {
    $redirect = $cnamer->redirect();
} catch (Exception $e) {
    error_log(json_encode(array("request" => $request, "error" => $e->getMessage())) . "\n", 3, CNAMER_DIR . 'logs/error.log');
    $destination = 'http://'. CNAMER_DOMAIN . '/error.php?request=' . $request['domain'] . '&uri=' . $request['uri'];
    http_redirect($destination, false, false, 302);
}

http_redirect($redirect['destination'], false, false, $redirect['options']['statuscode']);