<?php

namespace dLdL\WebService\Adapter;

use dLdL\WebService\ConnectorInterface;
use dLdL\WebService\Http\Request;
use Psr\Log\LoggerInterface;

class LoggerHelper
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function request(ConnectorInterface $connector, Request $request)
    {
        $this->logger->info(
            sprintf('Sending request to %s%s using %s.',
                $connector->getHost(), $request->getUrl(), $this->className($connector)
            ),
            $request->getParameters()
        );
    }

    public function response(ConnectorInterface $connector, $response, Request $request)
    {
        $this->logger->debug(
            sprintf('Response trace for %s%s.', $connector->getHost(), $request->getUrl()),
            [$response]
        );
    }

    public function cacheGet($host, Request $request, $cacheClass)
    {
        $this->logger->info(
            sprintf('Retrieving data for %s%s from cache %s.', $host, $request->getUrl(), $cacheClass),
            $request->getParameters()
        );
    }

    public function cacheAdd($host, Request $request, $cacheClass, $time)
    {
        $this->logger->info(
            sprintf('Adding response for %s%s to cache %s (will expire in %s seconds).',
                $host, $request->getUrl(), $cacheClass, $time
            ),
            $request->getParameters()
        );
    }

    public function connectionFailure(ConnectorInterface $connector, Request $request)
    {
        $this->logger->error(
            sprintf('Failed to connect to %s%s using %s.',
                $connector->getHost(), $request->getUrl(), $this->className($connector)
            ),
            $request->getParameters()
        );
    }

    public function requestFailure(ConnectorInterface $connector, Request $request, $exceptionMessage)
    {
        $this->logger->error(
            sprintf(
                'Failed to send request to %s%s using %s. Exception message : %s',
                $connector->getHost(), $request->getUrl(), $this->className($connector), $exceptionMessage
            ),
            $request->getParameters()
        );
    }

    private function className(ConnectorInterface $connector)
    {
        return (new \ReflectionClass($connector))->getShortName();
    }
}
