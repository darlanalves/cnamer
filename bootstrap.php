<?php

define('CNAMER_DOMAIN', 'cnamer.com');
define('CNAMER_DEMO', 'cnamer.org');
define('CNAMER_IP', '176.58.124.239');
define('CNAMER_DIR', __DIR__ . '/data/');

spl_autoload_register(function($class_name) {
    $file = __DIR__ . '/lib' . DIRECTORY_SEPARATOR . strtr($class_name, '\\', DIRECTORY_SEPARATOR) . ".php";
    if(is_readable($file))
        require_once $file;
});