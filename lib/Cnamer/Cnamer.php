<?php

namespace Cnamer;

class Cnamer{
    
    function __construct($domain) {
        $cname_record = $this->lookup('CNAME', $domain);
        $domain_type = 'sub';
        if(!$cname_record || $cname_record['type'] != "CNAME") {
            $domain_type = 'root';
            if(!$cname_record = $this->lookup('CNAME', "cnamer.{$domain}"))
                throw new Exception('Record cannot be found');
        }
        
        if($cname_record['target'] == 'txt.' . cnamer_domain) {
            $txt_id = ($domain_type == 'root') ? ('cnamer-root.' . $domain) : 'cnamer-' . $domain;
            if(!$txt_records = $this->lookup('TXT', $txt_id))
                throw new Exception('TXT Configuration not found');
            
            $domain_config = json_decode($txt_records[0]['txt'], true);
        } else {
            // cname configuration
            $domain_config = array();
        }
        
        $configuration = $this->compile_config($domain_config);
        
        $redirect = array(
            "destination" => "",
        );
        
        return $redirect;
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
        return array();
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