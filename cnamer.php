<?php

require_once __DIR__ . '/bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$cnamer = new Cnamer\Cnamer("appledave.co.uk");

    // CATCH EXCEPTIONS AND RENDER HELPFUL ERROR PAGE

// LOG THE STUFF
// maybe possibly have like, a live page that displays redirects done using pubnub?

// perform redirect