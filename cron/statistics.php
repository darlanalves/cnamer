<?php

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

$stats['redirect_count'] = $stats['redirect_count'] + $redirect_count;
$stats['domain_count'] = $domain_count;
$stats['last_update'] = time();

file_put_contents(CNAMER_DIR . 'stats/global.json', json_encode($stats, JSON_PRETTY_PRINT));

$template_values = array(
    "{CNAMER_DOMAIN}" => CNAMER_DOMAIN,
    "{CNAMER_DEMO}" => CNAMER_DEMO,
    "{CNAMER_IP}" => CNAMER_IP,
    "{REDIRECTS_COUNT}" => $stats['redirect_count'],
    "{DOMAINS_COUNT}" => $stats['domain_count'],
    "{LAST_UPDATE}" => $stats['last_update'],
);

$search = array_keys($template_values);
$replace = array_values($template_values);

$index = file_get_contents(__DIR__ . '/../templates/index.html');
$rendered_template = str_replace($search, $replace, $index);

file_put_contents(__DIR__ . '/../index.html', $rendered_template);