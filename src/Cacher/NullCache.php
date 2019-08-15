<?php

namespace roberthucks\SEOMagic\Cacher;

use roberthucks\SEOMagic\Containers\PageResponse;

class NullCache implements CacheInterface
{
    public function set(string $uri, PageResponse $data, int $ttl = null)
    {
        //
    }

    public function get(string $uri)
    {
        return false;
    }

    public function forget(string $uri)
    {
        //
    }

    public function has(string $uri): bool
    {
        return false;
    }

}