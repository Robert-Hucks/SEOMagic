<?php

namespace roberthucks\SEOMagic\Cacher;

use roberthucks\SEOMagic\Containers\PageResponse;

interface CacheInterface
{
    public function set(string $uri, PageResponse $data);
    public function get(string $uri);
    public function forget(string $uri);
    public function has(string $uri): bool;
}