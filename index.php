<?php

include 'vendor/autoload.php';

use roberthucks\SEOMagic\SEOMagic;
use roberthucks\SEOMagic\Configuration;

// Disable caching
//$configuration = Configuration::getInstance();
//$configuration->cache = roberthucks\SEOMagic\Cacher\NullCache::class;

$magic = new SEOMagic();

$magic->fetchPage('https://example.com');