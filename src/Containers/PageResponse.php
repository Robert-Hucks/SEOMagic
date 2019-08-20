<?php

namespace roberthucks\SEOMagic\Containers;

use ArrayObject;

class PageResponse extends ArrayObject
{
    protected $from_cache = false;
    protected $render_time;
    protected $html;
    protected $headers;
    protected $response_code;

    public function __construct(string $html, array $headers, int $response_code)
    {
        $this->html = $html;
        $this->headers = $headers;
        $this->response_code = $response_code;
    }

    public function setIsFromCache(): bool
    {
        return $this->from_cache = true;
    }

    public function isFromCache(): bool
    {
        return $this->from_cache;
    }

    public function getRenderTime(): float
    {
        return $this->render_time;
    }

    public function setRenderTime(float $render_time): float
    {
        return $this->render_time = $render_time;
    }

    public function getHtml(): string
    {
        return $this->html;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getResponseCode(): int
    {
        return $this->response_code;
    }
}