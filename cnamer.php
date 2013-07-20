<?php

    $domain = "cnamer.dev";
    $cname = $_SERVER['HTTP_HOST'];
    $destination_domain = str_replace('.' . $domain, "", $cname);
    $querystring = substr($_SERVER['REQUEST_URI'], 1);
    
    $options_str = explode("-opts-", $destination_domain);
    $options_string = $options_str[1];
    
    if(isset($options_str[1])) {
        $destination_domain = $options_str[0];
        preg_match_all('%(([\w]+).([\w]+))%', $options_string, $options_matches);
        foreach ($options_matches[2] as $key => $option) {
            $option_list[$option][] = $options_matches[3][$key];
        }
    }
    
    $default_options = array(
        "protocol" => array(
            "default" => "http",
        ),
        "query" => array(
            "default" => false,
        ),
        "statuscode" => array(
            "default" => 301,
        ),
        "querystring" => array(
            "default" => false,
            "join" => "/",
        ),
    );

    foreach($default_options as $dkey => $dproperties) {
        if(count($option_list[$dkey]) > 1) {
            $value = implode($dproperties["join"], $option_list[$dkey]);
        } else {
            $value = $option_list[$dkey][0] ?: $dproperties['default'];
        }

        if($value === "true" || $value === "false")
            $value = ($value === "true") ? true : (($value === "false") ? false : $value);

        $options[$dkey] = ($value ?: false);
    }
        
    $url = ($options['protocol']) . '://' . $destination_domain . '/' . ($options['querystring'] ?: false) . ($options['query'] ? $querystring : false);
    
    Header('HTTP/1.0 301');
    Header('Location: ' . $url);