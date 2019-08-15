<?php

namespace roberthucks\SEOMagic\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use roberthucks\SEOMagic\Configuration;

class RotatingFileLogger implements LogInterface
{
    protected $logger;

    public function __construct()
    {
        $configuration = Configuration::getInstance();

        $formatter = new LineFormatter("[%datetime%] %channel%.%level_name%: %message%\n");
        $stream = new RotatingFileHandler(
            rtrim($configuration->logfile_location, '/') . '/seomagic.log',
            $configuration->log_max_files,
            $configuration->logger_level
        );
        $stream->setFormatter($formatter);

        $this->logger = new Logger('seomagic');
        $this->logger->pushHandler($stream);
    }

    public function log(string $level, string $message, array $context = [])
    {
        $this->logger->$level($message, $context);
    }

    public function emergency(string $message, array $context = [])
    {
        $this->logger->emergency($message, $context);
    }

    public function alert(string $message, array $context = [])
    {
        $this->logger->alert($message, $context);
    }

    public function critical(string $message, array $context = [])
    {
        $this->logger->critical($message, $context);
    }

    public function error(string $message, array $context = [])
    {
        $this->logger->error($message, $context);
    }

    public function warning(string $message, array $context = [])
    {
        $this->logger->warning($message, $context);
    }

    public function notice(string $message, array $context = [])
    {
        $this->logger->notice($message, $context);
    }

    public function info(string $message, array $context = [])
    {
        $this->logger->info($message, $context);
    }

    public function debug(string $message, array $context = [])
    {
        $this->logger->debug($message, $context);
    }

}