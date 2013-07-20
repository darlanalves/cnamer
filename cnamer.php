<?php

    $domain = "cnamer.com";
    $cname = $_SERVER['HTTP_HOST'];
    $querystring = substr($_SERVER['REQUEST_URI'], 1);
    
    $options_str = explode("-opts-", $cname);
    
    $destination_domain = $options_str[0];
    $options_string = str_replace($domain, "", $options_str[1]);
    
    preg_match_all('%(([\w]+).([\w]+))%', $options_string, $options_matches);
    
    foreach ($options_matches[2] as $key => $option)
        $option_list[$option][] = $options_matches[3][$key];

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
            $value = $option_list[$dkey][0];
        }
        
        if(!isset($option_list[$dkey])) 
            $value = $default_options[$dkey]["default"];
        
        if($value === "true" || $value === "false")
            $value = ($value === "true") ? true : (($value === "false") ? false : $value);
        
        $options[$dkey] = ($value ?: false);
    }
    
    $url = ($options['protocol']) . '://' . $destination_domain . '/' . ($options['querystring'] ?: false) . '/' . ($options['query'] ? $querystring : false);
    
    Header('HTTP/1.0 301');
    Header('Location: ' . $url);