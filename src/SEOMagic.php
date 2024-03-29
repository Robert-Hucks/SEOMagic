<?php

declare(strict_types=1);

namespace roberthucks\SEOMagic;

use Nesk\Puphpeteer\Puppeteer;
use PHPHtmlParser\Dom;
use Nesk\Rialto\Exceptions\Node;
use roberthucks\SEOMagic\Cacher\CacheInterface;
use roberthucks\SEOMagic\Containers\PageResponse;
use roberthucks\SEOMagic\Exceptions\InvalidContainerDataException;
use roberthucks\SEOMagic\Exceptions\InvalidUriException;
use roberthucks\SEOMagic\Exceptions\NodeErrorException;
use roberthucks\SEOMagic\Logger\LogInterface;

/**
 * Class SEOMagic
 * @package roberthucks\SEOMagic
 */
class SEOMagic
{
    /**
     * Package version
     */
    const VERSION = "2.0.2";

    /**
     * @var \Nesk\Puphpeteer\Puppeteer
     */
    protected $puppeteer;

    /**
     * @var \PHPHtmlParser\Dom
     */
    protected $dom_parser;

    /**
     * @var \roberthucks\SEOMagic\Cacher\CacheInterface
     */
    protected $cacher;

    /**
     * @var \roberthucks\SEOMagic\Logger\LogInterface
     */
    protected $logger;

    /**
     * SEOMagic Constructor
     */
    public function __construct()
    {
        $this->logger = $this->getLogger();

        return $this;
    }

    /**
     * Fetches the requested page
     * 
     * @param string $uri
     * @param bool $fresh
     * 
     * @return \roberthucks\SEOMagic\Containers\PageResponse
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\InvalidUriException
     */
    public function fetchPage(string $uri, bool $fresh = false): PageResponse
    {
        $start_request = microtime(true);
        // If the URI is invalid, throw error
        if (! $this->validateUri($uri)) {
            $this->logger->error($uri . ' is not a valid URI');
            throw new InvalidUriException($uri . ' is not a valid URI');
        }

        // If page has already been cached and a fresh version isn't specifically requested, return that.
        if ($cached_page = $this->getCache()->get($uri)) {
            $cached_page->setIsFromCache();
            $this->logger->debug($uri . ' is being fetched from the cache', [
                'execution_time' => microtime(true) - $start_request
            ]);
            return $cached_page;
        }

        // Fetch live page, cache and return that.
        $page = $this->getPageContent($uri);
        $this->getCache()->set($uri, $page);
        $this->logger->debug($uri . ' is being fetched live', [
            'execution_time' => microtime(true) - $start_request
        ]);
        return $page;
    }

    /**
     * Caches the requested page
     * 
     * @param string $uri
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\InvalidUriException
     */
    public function cachePage($uri)
    {
        $start_request = microtime(true);
        // If the URI is invalid, throw error
        if (! $this->validateUri($uri)) {
            $this->logger->error($uri . ' is not a valid URI');
            throw new InvalidUriException($uri . ' is not a valid URI');
        }

        // If the page has already been cached, remove from the cache
        if ($this->getCache()->has($uri)) {
            $this->getCache()->forget($uri);
        }

        // Fetch and cache page
        $page = $this->getPageContent($uri);
        $this->getCache()->set($uri, $page);
        $this->logger->debug($uri . ' is being cached', [
            'execution_time' => microtime(true) - $start_request
        ]);
    }

    /**
     * @param string $uri
     * 
     * @return \roberthucks\SEOMagic\Containers\PageResponse
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\NodeErrorException
     */
    public function getPageContent(string $uri): PageResponse
    {
        $start_fetch = microtime(true);
        // Load page in Puppeteer and get HTML
        $browser = $this->getPuppeteer()->launch();
        $page = $browser->newPage();
        try {
            $page_response = $page->goto($uri, [
                'waitUntil' => [
                    'networkidle0',
                    'networkidle2',
                    'domcontentloaded',
                    'load'
                ]
            ]);

            $response = new PageResponse((string) $this->getDomParser()->loadStr( $page->content(), [
                'removeScripts' => true,
                'removeStyles' => true
            ]), $page_response->headers(), $page_response->status());
            $response->setRenderTime(microtime(true) - $start_fetch);

            return $response;
        } catch (Node\Exception $e) {
            $this->logger->error('An error has occurred with the Puppeteer/Node process.', ['exception' => $e]);
            throw new NodeErrorException('An error has occurred with the Puppeteer/Node process.');
        }
    }

    /**
     * @param string $uri
     * 
     * @return mixed
     */
    public function validateUri(string $uri)
    {
        return filter_var($uri, FILTER_VALIDATE_URL);
    }

    /**
     * @return \roberthucks\SEOMagic\Logger\LogInterface
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\InvalidContainerDataException
     */
    public function getLogger(): LogInterface
    {
        return $this->getConfiguration()->getLogger();
    }

    /**
     * @return \roberthucks\SEOMagic\Configuration
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\InvalidContainerDataException
     */
    public function getConfiguration(): Configuration
    {
        return Configuration::getInstance();
    }

    /**
     * @return \roberthucks\SEOMagic\Cacher\CacheInterface
     * 
     * @throws \roberthucks\SEOMagic\Exceptions\InvalidContainerDataException
     */
    public function getCache(): CacheInterface
    {
        if (! $this->cacher) {
            $this->cacher = $this->getConfiguration()->getCache();
        }

        return $this->cacher;
    }

    /**
     * @return \Nesk\Puphpeteer\Puppeteer
     */
    public function getPuppeteer(): Puppeteer
    {
        if (! $this->puppeteer) {
            $this->puppeteer = new Puppeteer([
                'executable_path' => $this->getConfiguration()->node_execution_path
            ]);
        }

        return $this->puppeteer;
    }

    /**
     * @return \PHPHtmlParser\Dom
     */
    public function getDomParser(): Dom
    {
        if (! $this->dom_parser) {
            $this->dom_parser = new Dom;
        }

        return $this->dom_parser;
    }
}