<?php

// check if CLI
// take param to force regeneration

require_once __DIR__ . '/../bootstrap.php';

$time = time();
$log_file = CNAMER_DIR . 'logs/redirect.log';

if(!file_exists($log_file))
    die('no redirects since last run');

rename($log_file, $log_file . '.' . $time);
$redirects = file_get_contents($log_file . '.' . $time);

$lines = explode("\n", $redirects);
array_pop($lines);

$redirect_domains = array();
$redirect_count = 0;
$domain_count = 0;

foreach($lines as $line) {
    preg_match('%\[(.*?)\] (.*?) (.*?)$%', $line, $values);
    list($entry, $datetime, $request_ip, $json) = $values;
    $data = json_decode($json, true);
    $redirect_domains[$data["domain"]] = true;
    $redirect_count++;
}

$new_domain_count = count($redirect_domains);

$cache_files = new FilesystemIterator(CNAMER_DIR . 'cache/', FilesystemIterator::SKIP_DOTS);
$domain_count = iterator_count($cache_files);

if(file_exists(CNAMER_DIR . 'stats/global.json')) {
    $stats = json_decode(file_get_contents(CNAMER_DIR . 'stats/global.json'), true);
} else {
    $stats = array(
        "redirect_count" => 0,
        "domain_count" => 0,
        "last_update" => $time,
    );
}

// lol what am i doing

$stats['redirect_count_period'] = $redirect_count;
$stats['redirect_count'] = $stats['redirect_count'] + $redirect_count;
$stats['domain_count_period'] = $domain_count - $stats['domain_count_period'];
$stats['domain_count'] = $domain_count;
$stats['period_length'] = time() - $stats['last_update'];
$stats['last_update'] = time();

foreach(array("domain", "redirect") as $type) {
    $period = "{$type}_count_period";
    $length = "{$type}_counter_length";
    $increment = "{$type}_counter_increment";
    
    if($stats[$period] > 0) {
        if(($rcp = $stats['period_length'] / $stats[$period] * 1000) < 500) {
            $stats[$length] = 500;
            $stats[$increment] = round(500 / $rcp);
        } else {
            $stats[$length] = round($rcp);
            $stats[$increment] = 1;
        }
    }
}

file_put_contents(CNAMER_DIR . 'stats/global.json', json_encode($stats, JSON_PRETTY_PRINT));