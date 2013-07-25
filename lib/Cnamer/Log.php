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
        $this->increment('redirects');
        file_put_contents(CNAMER_DIR . 'logs/redirect.log', $this->log_string . json_encode($request), FILE_APPEND);
    }
    
    function increment($key, $amount = 1) {
        $stats = array(
            "domains" => 0,
            "redirects" => 0,
        );
        
        if($global_stats = file_get_contents(CNAMER_DIR . 'stats/global.json')) {
            $stats = json_decode($global_stats, true);
        }
        
        $stats[$key] = $stats[$key] + $amount;
        file_put_contents(CNAMER_DIR . 'stats/global.json', json_encode($stats));
    }
    
    function error($error) {
        file_put_contents(CNAMER_DIR . 'logs/error.log', $this->log_string . $error, FILE_APPEND);
    }
    
}