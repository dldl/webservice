<?php

namespace dLdL\WebService;

use dLdL\WebService\Adapter\CacheHelperInterface;
use dLdL\WebService\Exception\ConnectionException;
use dLdL\WebService\Exception\RequestException;
use dLdL\WebService\Exception\WebServiceException;
use dLdL\WebService\Adapter\LoggerHelper;
use dLdL\WebService\Http\Request;
use dLdL\WebService\Adapter\ParameterBag;
use dLdL\WebService\Adapter\ParameterBagInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractConnector implements ConnectorInterface
{
    /**
     * @var null|CacheHelperInterface
     */
    private $cache;

    /**
     * @var ParameterBagInterface
     */
    private $parameters;

    /**
     * @var LoggerHelper
     */
    private $log;

    public function __construct()
    {
        $this->parameters = new ParameterBag();
    }

    abstract protected function handleRequest(Request $request);

    public function sendRequest(Request $request)
    {
        if (!$this->isConnected() || $this->getHost() === null) {
            throw new ConnectionException($this->currentConnector().' must be connected before sending data.');
        }

        if (!$this->supportsMethod($request->getMethod())) {
            throw new RequestException('Method '.$request->getMethod().' is not supported by '.$this->currentConnector().'.');
        }

        if ($this->hasCache() && $this->getCache()->getPool()->hasItem($request)) {
            return $this->getFromCache($request);
        }

        try {
            $this->log->request($this, $request);
            $response = $this->handleRequest($request);
            $this->log->response($this, $response, $request);

            if ($this->hasCache()) {
                $this->saveToCache($request, $response);
            }
        } catch (WebServiceException $e) {
            $this->log->requestFailure($this, $request, $e->getMessage());

            throw new WebServiceException('Unable to contact WebService : '.$e->getMessage());
        }

        return $response;
    }

    private function getFromCache(Request $request)
    {
        $this->log->cacheGet($this->getHost(), $request, (new \ReflectionClass($this->getCache()))->getShortName());

        $response = $this->getCache()->getPool()->getItem($request);

        $this->log->response($this, $response, $request);

        return $response;
    }

    private function saveToCache(Request $request, $response)
    {
        $duration = $this->getCache()->getConfig()->get($request, CacheHelperInterface::DEFAULT_DURATION);

        $item = $this->getCache()->getPool()
            ->getItem($request)
            ->set($response)
            ->expiresAfter($duration)
        ;

        $this->getCache()->getPool()->save($item);

        $this->log->cacheAdd($this, $request, (new \ReflectionClass($this->getCache()))->getShortName(), $duration);
    }

    private function currentConnector()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setCache(CacheHelperInterface $cache = null)
    {
        $this->cache = $cache;
    }

    public function hasCache()
    {
        return null !== $this->cache;
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->log = new LoggerHelper($logger);
    }
}
