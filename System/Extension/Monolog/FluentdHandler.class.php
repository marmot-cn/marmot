<?php
namespace System\Extension\Monolog;

use Fluent\Logger\FluentLogger;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

use Marmot\Core;

class FluentdHandler extends AbstractProcessingHandler
{
/**
     * @var FluentLogger|bool
     */
    private $logger;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $host;

    /**
     * Initialize Handler
     *
     * @param bool|string $host
     * @param int $port
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(
        $host = FluentLogger::DEFAULT_ADDRESS,
        $port = FluentLogger::DEFAULT_LISTEN_PORT,
        $level = Logger::DEBUG,
        $tag = '',
        $bubble = true
    ) {
        $this->port = $port;
        $this->host = $host;
        $this->tag = empty($tag) ? Core::$container->get('fluentd.tag') : $tag;
        if (!$this->host) {
            $this->logger = false; // disable logging if host is not provided
        }
        parent::__construct($level, $bubble);
    }

    private function lazyLoadLogger()
    {
        if ($this->logger || ($this->logger === false)) {
            // return if FluentLogger is already loaded or failed to load
            return $this->logger;
        }
        // Ensure service failure does not compromise the app
        try {
            $this->logger = new FluentLogger($this->host, $this->port);
        } catch (\Exception $e) {
            $this->logger = false;
        }
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        if (!$this->lazyLoadLogger()) {
            return;
        }

        $this->logger->post($this->tag, array('log'=>$record['formatted']));
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new JsonFormatter;
    }
}
