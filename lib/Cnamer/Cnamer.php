<?php

namespace Cnamer;
use Exception;

class Cnamer{
    
    function __construct($request) {
        $this->request = $request;
        $this->cache_key = str_replace(".", "-", $request['domain']);
        $this->cache_location = CNAMER_DIR . 'cache/';
        $this->cache_file = $this->cache_location . $this->cache_key . '.cache';
        $this->cache_time = 600;
        $this->log_dir = CNAMER_DIR . 'logs/';
    }
    
    function redirect() {
        $domain = $this->request['domain'];
        
        if($cached_data = $this->cache_retrieve($this->cache_time)) {
            $this->log_request();
            return $cached_data;
        }
        
        $cname_record = $this->lookup('CNAME', $domain);
        $domain_type = 'sub';
        if(!$cname_record || $cname_record['type'] != "CNAME") {
            $domain_type = 'root';
            if(!$cname_record = $this->lookup('CNAME', "cnamer.{$domain}"))
                throw new \Exception('Record cannot be found ' . "cnamer.{$domain}");
        }
        
        if($cname_record['target'] == 'txt.' . CNAMER_DOMAIN) {
            $txt_id = ($domain_type == 'root') ? ('cnamer-root.' . $domain) : 'cnamer-' . $domain;
            if(!$txt_records = $this->lookup('TXT', $txt_id))
                throw new \Exception('TXT Configuration not found ' . "cnamer-root.{$domain}");
            
            $domain_config = json_decode($txt_records[0]['txt'], true);
            
            if(substr($domain_config['destination'], -1) == '/')
                $domain_config['destination'] = substr($domain_config['destination'], 0, -1);
            
            $domain_config['source'] = 'txt';
        } else {
            $target = $cname_record['target'];
            $opts = explode("-opts-", $target);
            
            $domain_config['source'] = 'cname';
            $domain_config["destination"] = $opts[0];
            
            if(isset($opts[1])) {
                $options = explode("-", str_replace('.' . CNAMER_DOMAIN, "", $opts[1]));
                foreach($options as $option) {
                    $o = explode(".", $option);
                    $option_values[$o[0]][] = $o[1];
                }
                
                foreach($option_values as $option => $values) {
                    if(count($values) > 1) {
                        $domain_config['options'][$option] = $values;
                    } else {
                        $domain_config['options'][$option] = $values[0];
                    }
                }
            }
        }
        
        $this->log_request();
        
        if(isset($this->request['uri']))
            $domain_config['request_uri'] = $this->request['uri'];
        
        $configuration = $this->compile_config($domain_config);
        $destination = $this->render_destination($configuration);
        
        $redirect = array(
            "destination" => $destination,
            "statuscode" => $configuration['statuscode'],
        );
        
        $this->cache_store($redirect);
        
        return $redirect;
    }
    
    function lookup($type, $domain) {
        $lookup = "lookup_{$type}";
        return $this->$lookup($domain);
    }
    
    function compile_config($config) {
        $options = array(
            "protocol" => array(
                "value" => "http",
                "join" => false,
                "unsupported_type" => "txt",
            ),
            "statuscode" => array(
                "value" => "301",
                "join" => false,
            ),
            "uri" => array(
                "value" => false,
                "join" => false,
            ),
            "uristring" => array(
                "value" => false,
                "join" => "/",
            ),
        );
        
        $c_options = array();
        foreach($options as $option => $properties) {
            if (isset($options[$option]["unsupported_type"]) && $options[$option]["unsupported_type"] == $config['source']) {
                $c_options[$option] = false;
            } elseif (isset($config["options"][$option]) && is_array($config["options"][$option]) && $properties['join']) {
                $c_options[$option] = implode("/", $config['options'][$option]);
            } elseif (isset($config["options"][$option])) {
                $c_options[$option] = $config["options"][$option];
            } else {
                $c_options[$option] = $properties["option"]["value"]
            }
        }
        
        return array_merge($c_options, array("destination" => $config['destination'], "request_uri" => $config['request_uri']));
    }
    
    function render_destination($config) {
        $destination =
        ($config['protocol'] ? $config['protocol'] . '://' : false) .
        $config['destination'] .
        ($config['uristring'] ? '/' . $config['uristring'] : false) .
        ($config['request_uri'] ? $config['request_uri'] : false);
        
        return $destination;
    }
    
    function lookup_CNAME($domain) {
        $record = dns_get_record($domain, DNS_CNAME);
        return $record ? $record[0] : false;
    }
    
    function lookup_TXT($domain) {
        return dns_get_record($domain, DNS_TXT);
    }
    
    function cache_retrieve($expiry) {
        if($this->cache_time == 0)
            return false;
        
        if(file_exists($this->cache_file) && filemtime($this->cache_file) >= time() - $expiry)
            return json_decode(file_get_contents($this->cache_file), true);
        
        return false;
    }
    
    function cache_store($data) {
        file_put_contents($this->cache_file, json_encode($data));
    }
    
    function request_value($value) {
        return $this->$value;
    }
    
    function log_request() {
        $client = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI');
        
        $line = '[' . date("Y-m-d H:i:s") . '] ' . $client . ' ' . json_encode(array_merge(array("time" => time()), $this->request)) . "\n";
        file_put_contents($this->log_dir . 'redirect.log', $line, FILE_APPEND);
    }
    
    function cache_time($cache_time) {
        $this->cache_time = $cache_time;
    }
    
}