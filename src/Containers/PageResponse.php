<?php

namespace roberthucks\SEOMagic\Containers;

use ArrayObject;
use Nesk\Puphpeteer\Resources\Response;
use PHPHtmlParser\Dom;

class PageResponse extends ArrayObject
{
    protected $response;
    protected $dom_parser;
    protected $from_cache = false;
    protected $render_time;
    protected $html;
    protected $headers;
    protected $response_code;

    public function __construct(Response $response)
    {
        $this->response = $response;
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
        if (! $this->html) {
            $this->html = $this->getDomParser()->loadStr($this->response->text(), [
                'removeScripts' => true,
                'removeStyles' => true
            ]);
        }

        return $this->html;
    }

    public function getHeaders(): array
    {
        if (! $this->headers) {
            $this->headers = $this->response->headers();
        }

        return $this->headers;
    }

    public function getResponseCode(): int
    {
        if (! $this->response_code) {
            $this->response_code = $this->response->status();
        }

        return $this->response_code;
    }

    public function getDomParser(): Dom
    {
        if (! $this->dom_parser) {
            $this->dom_parser = new Dom;
        }

        return $this->dom_parser;
    }
}