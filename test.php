<?php

require_once __DIR__ . '/bootstrap.php';

$requests = array(
    "github" => array( // txt record
        "domain" => "github.cnamer.org",
        "uri" => "",
    ),
    "wikipedia" => array( // cname
        "domain" => "wikipedia.cnamer.org",
        "uri" => "",
    ),
    "wikipediassl" => array( // cname
        "domain" => "wikipediassl.cnamer.org",
        "uri" => "",
    ),
    "support" => array( // cname with uri preset
        "domain" => "support.mcf.li",
        "uri" => "whatever",
    ),
    "search" => array( // cname with uri true
        "domain" => "search.mcf.li",
        "uri" => "something+here",
    ),
    "cnamer" => array( // cnamer url
        "domain" => "google.com.cnamer.com",
        "uri" => "",
    ),
    "cnameopts" => array( // cnamer url with opts
        "domain" => "google.com-opts-statuscode.301.cnamer.com",
        "uri" => "",
    ),
    "google" => array( // none cnamer
        "domain" => "google.com",
        "uri" => ""
    ),
    "httpstatuses" => array( // cname with uri 
        "domain" => "httpstatuses.com",
        "uri" => "404",
    ),
);

foreach($requests as $name => $request) {
    $cnamer = new Cnamer\Cnamer(array("cache_use" => false));
    try {
        $redirect = $cnamer->redirect($request);
        $results[$name] = json_encode($redirect);
    } catch (Exception $e) {
        $results[$name] = $e->getMessage();
    }
}

print_r($results);