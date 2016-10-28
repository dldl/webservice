<?php

namespace dLdL\WebService\Adapter;

use dLdL\WebService\Http\Request;
use Psr\Log\LoggerInterface;

class LoggerHelper
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function request($host, Request $request, $class)
    {
        $this->logger->info(
            sprintf('Sending request to %s using adapter %s.', $host.'/'.$request->getUrl(), $class),
            $request->getParameters()
        );
    }

    public function response($host, $response, Request $request)
    {
        $this->logger->debug(
            sprintf('Response trace for request to %s.', $host.'/'.$request->getUrl()),
            [$response]
        );
    }

    public function cacheGet($host, Request $request, $class)
    {
        $this->logger->info(
            sprintf('Retrieving data for url %s from cache %s.', $host.'/'.$request->getUrl(), $class),
            $request->getParameters()
        );
    }

    public function cacheAdd($host, Request $request, $class, $time)
    {
        $this->logger->info(
            sprintf('Adding data to cache %s for url %s (will expire in %s seconds).', $class, $host.'/'.$request->getUrl(), $time),
            $request->getParameters()
        );
    }

    public function connectionFailure($host, Request $request, $class)
    {
        $this->logger->error(
            sprintf('Failed to connect to %s using the adapter %s.', $host.'/'.$request->getUrl(), $class),
            $request->getParameters()
        );
    }

    public function requestFailure($host, Request $request, $class, $exceptionMessage)
    {
        $this->logger->error(
            sprintf(
                'Failed to send request to %s using the adapter %s. Exception message : %s',
                $host.'/'.$request->getUrl(),
                $class,
                $exceptionMessage
            ),
            $request->getParameters()
        );
    }
}
