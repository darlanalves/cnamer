<?php

namespace Cnamer;
use Exception;

class Cnamer {

    function __construct($config = false) {
        $this->cache_time = 600;
        $this->cache_use = true;
        
        $this->log_dir = CNAMER_DIR . 'logs/';
        $this->log_use = true;

        $this->options = array(
            "protocol" => array(
                "value" => "http",
                "join" => false,
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
            "destination" => array(
                "value" => "",
            ),
        );
        
        if ($config) {
            foreach ($config as $option => $value) {
                $this->$option = $value;
            }
        }
    }

    function redirect($request) {
        if(substr($request['domain'], -strlen(CNAMER_DOMAIN)) != CNAMER_DOMAIN) {
            $cname_record = $this->dns_lookup('CNAME', $request['domain']);
            $domain_type = 'sub';
            
            if(!$cname_record || $cname_record['type'] != 'CNAME') {
                $domain_type = 'root';
                if(!$cname_record = $this->dns_lookup('CNAME', 'cnamer.' . $request['domain'])) {
                    throw new \Exception('Record not be found');
                }
            }
            
            if($cname_record['target'] == 'txt.' . CNAMER_DOMAIN) {
                $txt_id = ($domain_type == 'root') ? ('cnamer-root.' . $request['domain']) : 'cnamer-' . $request['domain'];
                if(!$txt_record = $this->dns_lookup('TXT', $txt_id)) {
                    throw new \Exception('TXT Configuration not found');
                }
                
                $json_options = json_decode($txt_record['txt'], true);
                foreach($json_options as $key => $value)
                    $options[$key][] = $value;
            }
            
            if(!isset($options))
                $options = $this->parse_opts($cname_record['target']);
            
        } elseif (substr($request['domain'], -strlen(CNAMER_DOMAIN)) == CNAMER_DOMAIN) {
            $options = $this->parse_opts($request['domain']);
        } else {
            throw new \Exception('Error parsing / fetching data due to invalud domain');
        }
        
        $options = $this->render_options($options);
        
        if(isset($request['uri'])) 
            $options['request_uri'] = $request['uri'];
        
        $destination = $this->compile_destination($options);
        
        $redirect = array(
            "destination" => $destination,
            "options" => $options,
        );
        
        return $redirect;
    }
    
    function dns_lookup($type, $domain) {
        $cache_key = str_replace(".", "-", $domain);
        $cache_location = CNAMER_DIR . 'cache/';
        $cache_file = $cache_location . $cache_key . ".{$type}.cache";
        
        if($this->cache_use == true && file_exists($cache_file) && filemtime($cache_file) >= time() - $this->cache_time) {
            return json_decode(file_get_contents($cache_file), true);
        }
        
        $lookup = "lookup_{$type}";
        $data = $this->$lookup($domain);
        
        if($this->cache_use) {
            file_put_contents($cache_file, json_encode($data, JSON_PRETTY_PRINT));
        }
        
        return $data;
    }
    
    function lookup_CNAME($domain) {
        if(!$cname = dns_get_record($domain, DNS_CNAME))
            return false;
        
        return $cname[0];
    }
    
    function lookup_TXT($domain) {
        $record = dns_get_record($domain, DNS_TXT);
        return $record ? $record[0] : false;
    }
    
    function parse_opts($domain) {
        $domain = substr($domain, 0, -strlen(CNAMER_DOMAIN) - 1);
        if(strpos($domain, '-opts-')) {
            $dn = explode('-opts-', $domain);
            $options = explode('-', $dn[1]);
            $domain_options = array();
            
            foreach($options as $option) {
                $opt = explode(".", $option);
                $cname_options[$opt[0]][] = $opt[1];
            }
            
            $domain = $dn[0];
        }
        
        $cname_options['destination'][] = $domain;
        
        return $cname_options;
    }
    
    function render_options($options) {
        foreach($this->options as $option => $properties) {
            if(isset($options[$option])) {
                if(isset($properties['join']) && count($options[$option]) > 1) {
                    $values[$option] = implode($properties['join'], $options[$option]);
                } else {
                    $values[$option] = $options[$option][0];
                }
            } else {
                $values[$option] = $properties['value'];
            }
        }
        
        return $values;
    }
    
    function compile_destination($options) {
        $destination = $options['destination'];
        
        if(filter_var($destination, FILTER_VALIDATE_URL) === false)
            $destination = $options['protocol'] . '://' . $destination;
        
        if($options['uristring'])
            $destination .= '/' . $options['uristring'];
        
        if($options['uri'] && !empty($options['request_uri']))
            $destination .= '/' . $options['request_uri'];
        
        return $destination;
    }
    
}