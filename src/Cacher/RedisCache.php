<?php

namespace roberthucks\SEOMagic\Cacher;

use Predis\Client;
use roberthucks\SEOMagic\Configuration;
use roberthucks\SEOMagic\Containers\PageResponse;
use roberthucks\SEOMagic\Traits\HashesStrings;

class RedisCache implements CacheInterface
{
    use HashesStrings;

    protected $redis;

    public function __construct(Client $redis = null)
    {
        if (is_null($redis)) {
            $configuration = Configuration::getInstance();

            $this->redis = new Client($configuration->redis_cache_location . '?' . http_build_query([
                'database' => $configuration->redis_database,
                'password' => $configuration->redis_auth,
            ]), [
                'prefix' => $configuration->redis_cache_prefix
            ]);

            return;
        }

        $this->redis = $redis;
    }

    public function buildCacheKey(string $uri): string
    {
        return $this->hashString($uri);
    }

    public function set(string $uri, PageResponse $data)
    {
        $this->redis->set($this->buildCacheKey($uri), serialize($data));
        $this->redis->expire($this->buildCacheKey($uri), Configuration::getInstance()->redis_cache_default_ttl);
    }

    public function get(string $uri)
    {
        if (! $this->has($uri)) {
            return false;
        }

        $data = unserialize($this->redis->get($this->buildCacheKey($uri)));

        return $data;
    }

    public function forget(string $uri)
    {
        return $this->redis->del([$this->buildCacheKey($uri)]);
    }

    public function has(string $uri): bool
    {
        return $this->redis->exists($this->buildCacheKey($uri));
    }

}