<?php

namespace dLdL\WebService\Adapter;

use dLdL\WebService\AdapterInterface;
use dLdL\WebService\Http\Request;
use Psr\Log\LoggerInterface;

class LoggerHelper
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function request(AdapterInterface $adapter, Request $request)
    {
        $this->logger->info(
            sprintf('Sending request to %s%s using %s.',
                $adapter->getHost(), $request->getUrl(), $this->className($adapter)
            ),
            $request->getParameters()
        );
    }

    public function response(AdapterInterface $adapter, $response, Request $request)
    {
        $this->logger->debug(
            sprintf('Response trace for %s%s.', $adapter->getHost(), $request->getUrl()),
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

    public function connectionFailure(AdapterInterface $adapter, Request $request)
    {
        $this->logger->error(
            sprintf('Failed to connect to %s%s using %s.',
                $adapter->getHost(), $request->getUrl(), $this->className($adapter)
            ),
            $request->getParameters()
        );
    }

    public function requestFailure(AdapterInterface $adapter, Request $request, $exceptionMessage)
    {
        $this->logger->error(
            sprintf(
                'Failed to send request to %s%s using %s. Exception message : %s',
                $adapter->getHost(), $request->getUrl(), $this->className($adapter), $exceptionMessage
            ),
            $request->getParameters()
        );
    }

    private function className(AdapterInterface $adapter)
    {
        return (new \ReflectionClass($adapter))->getShortName();
    }
}
