<?php

namespace Cnamer;
use Exception;

class Log{
    
    function __construct($request) {
        $this->cache_key = str_replace(".", "-", $request['domain']);
        $this->request = $request;
        $this->log_string = "\n" . '[' . date("Y-m-d H:i:s") . '] ' . $_SERVER['REMOTE_ADDR'] . ' ';
    }
    
    function redirect($request) {
        file_put_contents(CNAMER_DIR . 'logs/redirect.log', $this->log_string . json_encode($request), FILE_APPEND);
    }
    
    function error($error) {
        file_put_contents(CNAMER_DIR . 'logs/error.log', $this->log_string . $error, FILE_APPEND);
    }
    
}