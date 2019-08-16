<?php

namespace roberthucks\SEOMagic\Containers;

use Monolog\Logger;
use roberthucks\SEOMagic\Cacher\RedisCache;
use roberthucks\SEOMagic\Logger\RotatingFileLogger;
use roberthucks\SEOMagic\Traits\ConstructsContainers;
use roberthucks\SEOMagic\Traits\ValidatesContainers;

class MagicConfiguration extends AbstractArrayAccess
{
    use ConstructsContainers;
    use ValidatesContainers;

    protected $data = [
        // Logger
        'logger' => RotatingFileLogger::class,
        'logger_level' => Logger::DEBUG,
        'logfile_location' => 'logs/',
        'log_max_files' => 10,
        // Cache
        'cache' => RedisCache::class,
        'redis_cache_location' => 'tcp://127.0.0.1',
        'redis_cache_prefix' => 'seomagic:',
        'redis_cache_default_ttl' => 86400
    ];
}