<?php

declare(strict_types=1);

namespace roberthucks\SEOMagic;

use Nesk\Puphpeteer\Puppeteer;
use roberthucks\SEOMagic\Cacher\CacheInterface;
use roberthucks\SEOMagic\Containers\PageResponse;
use roberthucks\SEOMagic\Exceptions\InvalidContainerDataException;
use roberthucks\SEOMagic\Exceptions\InvalidUriException;
use roberthucks\SEOMagic\Logger\LogInterface;

class SEOMagic
{
    const VERSION = "0.0.1";

    protected $puppeteer;
    protected $cacher;
    protected $logger;
    protected $page_content;

    public function __construct()
    {
        $this->logger = $this->getLogger();

        return $this;
    }

    public function fetchPage(string $uri, bool $fresh = false): PageResponse
    {
        if (! $this->validateUri($uri)) {
            $this->logger->error($uri . ' is not a valid URI');
            throw new InvalidUriException($uri . ' is not a valid URI');
        }

        if ($cached_page = $this->getCache()->get($uri) && $fresh = false) {
            $this->logger->debug($uri . ' is being fetched from the cache');
            return $cached_page;
        }

        $this->logger->debug($uri . ' is being fetched live');
        return new PageResponse("<h1>Hello</h1>");
    }

    public function validateUri(string $uri)
    {
        return filter_var($uri, FILTER_VALIDATE_URL);
    }

    public function getLogger(): LogInterface
    {
        return $this->getConfiguration()->getLogger();
    }

    public function getConfiguration(): Configuration
    {
        return Configuration::getInstance();
    }

    public function getCache(): CacheInterface
    {
        if (! $this->cacher) {
            $this->cacher = $this->getConfiguration()->getCache();
        }

        return $this->cacher;
    }

    public function getPuppeteer(): Puppeteer
    {
        if (! $this->puppeteer) {
            $this->puppeteer = new Puppeteer;
        }

        return $this->puppeteer;
    }
}