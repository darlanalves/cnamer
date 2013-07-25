<?php

require_once __DIR__ . '/bootstrap.php';

$request = array(
    "domain" => $_SERVER['HTTP_HOST'],
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

$request = array(
    "domain" => "uristring.appledave.co.uk",
    "uri" => substr($_SERVER['REQUEST_URI'], 1) != "" ? $_SERVER['REQUEST_URI'] : false,
);

try {
    #$cnamer = new Cnamer\Cnamer("opts.appledave.co.uk");
   $cnamer = new Cnamer\Cnamer($request);
   $redirect = $cnamer->redirect();
   #$cnamer = new Cnamer\Cnamer("appledave.co.uk");
} catch (Exception $e) {
    print_r($e);
    // render helpful error page
}

// LOG THE STUFF
// maybe possibly have like, a live page that displays redirects done using pubnub?

# Header("HTTP/1.0 {$redirect['statuscode']}");
# Header("Location: {$redirect['destination']}");