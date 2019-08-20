<?php

include 'vendor/autoload.php';

use roberthucks\SEOMagic\SEOMagic;
use roberthucks\SEOMagic\Configuration;

// Disable caching
$configuration = Configuration::getInstance();
//$configuration->cache = roberthucks\SEOMagic\Cacher\NullCache::class;

$magic = new SEOMagic();

$page = $magic->fetchPage('https://example.com');

print_r([
    'html' => $page->getHtml(),
    'headers' => $page->getHeaders(),
    'response_code' => $page->getResponseCode(),
    'from_cache' => $page->isFromCache() ? 'true' : 'false',
    'render_time' => $page->getRenderTime()
]);