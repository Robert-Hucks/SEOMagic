<?php

namespace roberthucks\SEOMagic;

use roberthucks\SEOMagic\Cacher\CacheInterface;
use roberthucks\SEOMagic\Logger\LogInterface;
use roberthucks\SEOMagic\Containers\MagicConfiguration;
use roberthucks\SEOMagic\Exceptions\InvalidConfigurationException;

class Configuration
{
    private static $instance;

    protected $logger;
    protected $cache;
    protected $configuration;

    public function __construct()
    {
        $this->configuration = new MagicConfiguration;
    }

    public static function getInstance(): self
    {
        if (! self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration(MagicConfiguration $configuration)
    {
        if (! $configuration->valid()) {
            throw new InvalidConfigurationException('The configuration is invalid');
        }

        $this->configuration = $configuration;
    }

    public function getLogger(): LogInterface
    {
        if (! $this->logger) {
            $this->logger = new $this->configuration->logger;
        }

        return $this->logger;
    }

    public function getCache(): CacheInterface
    {
        if (! $this->cache) {
            $this->cache = new $this->configuration->cache;
        }

        return $this->cache;
    }

    public function __get(string $name)
    {
        return $this->configuration->$name;
    }

    public function __set(string $name, string $value)
    {
        return $this->configuration->$name = $value;
    }
}