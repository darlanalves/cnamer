<?php

namespace Cnamer;
use Exception;

class Cnamer {

    function __construct($request, $options = false) {
        $this->request = $request;
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

    function redirect() {
        if($this->cache_use && $cache_data = $this->cache_retrieve($this->cache_time)) {
            $this->domain_config = $cache_data;
        }elseif ($this->parse_domain($this->request['domain'])['type'] == 'domain') {
            $cname_record = $this->dns_lookup('CNAME', $this->request['domain']);
            $domain_type = 'sub';

            if (!$cname_record || $cname_record['type'] != 'CNAME') {
                $domain_type = 'root';
                if (!$cname_record = $this->dns_lookup('CNAME', "cnamer.{$this->request['domain']}")) {
                    throw new \Exception('Record cannot be found ' . "cnamer.{$this->request['domain']}");
                }
            }
            
            if ($cname_record['target'] == 'txt.' . CNAMER_DOMAIN) {
                $txt_id = ($domain_type == 'root') ? ('cnamer-root.' . $this->request['domain']) : 'cnamer-' . $this->request['domain'];
                if (!$txt_records = $this->dns_lookup('TXT', $txt_id)) {
                    throw new \Exception('TXT Configuration not found ' . "cnamer-root.{$this->request['domain']}");
                }
                
                $this->domain_config = array_merge(json_decode($txt_records[0]['txt'], true), array('source' => 'txt'));
            } else {
                $this->parse_domain($cname_record['target']);
            }
        } else {
            if(!isset($this->domain_config))
                throw new \Exception('Error setting the domain config ' . "{$this->request['domain']}");
        }
        
        if($this->cache_use) {
            $this->cache_store($this->domain_config);
        }
        
        if (isset($this->request['uri'])) {
            $this->domain_config['request_uri'] = substr($this->request['uri'], 1);
        }

        $configuration = $this->compile_config($this->domain_config);
        $destination = $this->render_destination($configuration);

        $redirect = array(
            "destination" => $destination,
            "statuscode" => $configuration['statuscode'],
        );

        $this->log_request();
        
        return $redirect;
    }

    function dns_lookup($type, $domain) {
        $lookup = "lookup_{$type}";
        return $this->$lookup($domain);
    }

    function parse_domain($domain) {
        $domain_config['type'] = (substr($domain, -strlen(CNAMER_DOMAIN)) == CNAMER_DOMAIN) ? 'cnamer' : 'domain';
        
        if (strpos($domain, '-opts-')) {
            $opts = explode("-opts-", $domain);

            $domain_config['destination'] = $opts[0];
            $domain_config['source'] = 'cname';

            $options = explode("-", str_replace('.' . CNAMER_DOMAIN, "", $opts[1]));
            foreach ($options as $option) {
                $o = explode(".", $option);
                $option_values[$o[0]][] = $o[1];
            }

            foreach ($option_values as $option => $values) {
                if (count($values) > 1) {
                    $domain_config['options'][$option] = $values;
                } else {
                    $domain_config['options'][$option] = $values[0];
                }
            }
        } else {
            $domain_config['destination'] = substr($domain, 0, -strlen(CNAMER_DOMAIN) - 1);
            $domain_config['source'] = 'cname';
        }

        $this->domain_config = $domain_config;
        return $domain_config;
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
        foreach ($options as $option => $properties) {
            if (isset($options[$option]["unsupported_type"]) && $options[$option]["unsupported_type"] == $config['source']) {
                $c_options[$option] = false;
            } elseif (isset($config["options"][$option]) && is_array($config["options"][$option]) && $properties['join']) {
                $c_options[$option] = implode("/", $config['options'][$option]);
            } elseif (isset($config["options"][$option])) {
                $c_options[$option] = $config["options"][$option];
            } else {
                $c_options[$option] = $properties["value"];
            }
        }

        return array_merge($c_options, array("destination" => $config['destination'], "request_uri" => $config['request_uri']));
    }

    function render_destination($config) {
        $destination =
                ($config['protocol'] ? $config['protocol'] . '://' : false) .
                ($config['destination']) .
                ($config['uristring'] ? '/' . $config['uristring'] : false) .
                ($config['request_uri'] ? '/' . $config['request_uri'] : false);

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
        if (file_exists($this->cache_file) && filemtime($this->cache_file) >= time() - $expiry)
            return json_decode(file_get_contents($this->cache_file), true);

        return false;
    }

    function cache_store($data) {
        file_put_contents($this->cache_file, json_encode($data));
    }
    
    function log_request() {
        if(!$this->log_use)
            return false;
        
        $client = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'CLI');

        $line = '[' . date("Y-m-d H:i:s") . '] ' . $client . ' ' . json_encode(array_merge(array("time" => time()), $this->request)) . "\n";
        file_put_contents($this->log_dir . 'redirect.log', $line, FILE_APPEND);
    }
}