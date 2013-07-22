<?php

define('cnamer_domain', 'cnamer.com');
define('cnamer_ip', '192.168.1.1');

spl_autoload_register(function($class_name) {
    $file = __DIR__ . '/lib' . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . ".php";
    if(is_readable($file))
        require_once $file;
});