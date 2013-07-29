<?php

namespace Cnamer;
use Exception;

class Cnamer {

    function __construct($options = false) {
        $this->cache_key = str_replace(".", "-", $request['domain']);

        $this->cache_location = CNAMER_DIR . 'cache/';
        $this->cache_file = $this->cache_location . $this->cache_key . '.cache';
        $this->log_dir = CNAMER_DIR . 'logs/';

        $this->cache_time = 600;
        $this->cache_use = true;
        
        $this->log_use = true;

        if ($options) {
            foreach ($options as $option => $value) {
                $this->$option = $value;
            }
        }
    }
    
}