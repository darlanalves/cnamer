<?php

require_once __DIR__ . '/bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$cnamer = new Cnamer\Cnamer($request);
$log = new Cnamer\Log($request);

try {
    $redirect = $cnamer->redirect();
    $log->redirect(array_merge($request, array("time" => time())));
} catch (Exception $e) {
    $log->error(json_encode(array("request" => $request, "error" => $e->getMessage())));
}

# Header("HTTP/1.0 {$redirect['statuscode']}");
# Header("Location: {$redirect['destination']}");