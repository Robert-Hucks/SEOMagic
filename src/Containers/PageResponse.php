<?php

namespace roberthucks\SEOMagic\Containers;

use ArrayObject;
use Carbon\Carbon;

class PageResponse extends ArrayObject
{
    public $raw_html;

    protected $from_cache = false;

    public function __construct(string $raw_html)
    {
        $this->raw_html = $raw_html;
    }

    public function setIsFromCache(): bool
    {
        return $this->from_cache = true;
    }

    public function isFromCache(): bool
    {
        return $this->from_cache;
    }
}