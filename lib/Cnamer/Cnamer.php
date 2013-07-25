<?php

namespace Cnamer;
use Exception;

class Cnamer{
    
    function __construct($request) {
        $domain = $request['domain'];
        
        $cname_record = $this->lookup('CNAME', $domain);
        $domain_type = 'sub';
        if(!$cname_record || $cname_record['type'] != "CNAME") {
            $domain_type = 'root';
            if(!$cname_record = $this->lookup('CNAME', "cnamer.{$domain}"))
                throw new \Exception('Record cannot be found');
        }
        
        if($cname_record['target'] == 'txt.' . cnamer_domain) {
            $txt_id = ($domain_type == 'root') ? ('cnamer-root.' . $domain) : 'cnamer-' . $domain;
            if(!$txt_records = $this->lookup('TXT', $txt_id))
                throw new \Exception('TXT Configuration not found');
            
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
                $options = explode("-", str_replace('.' . cnamer_domain, "", $opts[1]));
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
        
        if(!empty($request['uri']))
            $domain_config['request_uri'] = $request['uri'];
        
        $configuration = $this->compile_config($domain_config);
        $destination = $this->render_destination($configuration);
        
        $redirect = array(
            "destination" => $destination,
            "statuscode" => $configuration['statuscode'],
        );
        
        $this->redirect = $redirect;
    }
    
    function redirect() {
        return $this->redirect;
    }
    
    function lookup($type, $domain, $cache = 500) {
        // look in cache for data
        
        // retrieve data
        $lookup = "lookup_{$type}";
        return $this->$lookup($domain);
        
        // cache data
        
        // return data
    }
    
    function compile_config($config) {
        // return the compiled configuration
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
                // do nothing 'cause we're not allowed
                $c_options[$option] = false;
            } elseif (isset($config["options"][$option]) && is_array($config["options"][$option]) && $properties['join']) {
                $c_options[$option] = implode("/", $config['options'][$option]);
            } elseif (isset($config["options"][$option])) {
                $c_options[$option] = $config["options"][$option];
            } else {
                $c_options[$option] = false;
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
    
    function cache_retrieve($domain) {
        
    }
    
    function cache_store($data) {
        
    }
    
}