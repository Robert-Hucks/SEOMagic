<?php

include 'vendor/autoload.php';

use roberthucks\SEOMagic\SEOMagic;
use roberthucks\SEOMagic\Configuration;

// Disable caching
$configuration = Configuration::getInstance();
$configuration->cache = roberthucks\SEOMagic\Cacher\NullCache::class;

$magic = new SEOMagic();


file_put_contents('page-html.html', $magic->fetchPage('https://dev.ticketpass.org/event/EQODSS')->raw_html);
//  https://dev.ticketpass.org/event/EQODSS

