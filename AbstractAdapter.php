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

abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var null|CacheHelperInterface
     */
    protected $cache;

    /**
     * @var ParameterBagInterface
     */
    protected $parameters;

    /**
     * @var LoggerHelper
     */
    protected $log;

    public function __construct(LoggerInterface $logger)
    {
        $this->cache = null;
        $this->parameters = new ParameterBag();
        $this->log = new LoggerHelper($logger);
    }

    abstract protected function handleRequest(Request $request);

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

    public function sendRequest(Request $request)
    {
        if (!$this->isConnected() || $this->getHost() === null) {
            throw new ConnectionException(
                (new \ReflectionClass($this))->getShortName().' must be connected before sending data.'
            );
        }

        if (!$this->supportsMethod($request->getMethod())) {
            throw new RequestException(
                'Method '.$request->getMethod().' is not supported by '.(new \ReflectionClass($this))->getShortName().'.'
            );
        }

        if ($this->hasCache() && $this->cache->getPool()->hasItem($request)) {
            return $this->getFromCache($request);
        }

        try {
            $this->log->request($this->getHost(), $request, (new \ReflectionClass($this))->getShortName());
            $response = $this->handleRequest($request);
            $this->log->response($this->getHost(), $response, $request);

            if ($this->hasCache()) {
                $this->saveToCache($request, $response);
            }
        } catch (WebServiceException $e) {
            $this->log->requestFailure($this->getHost(), $request, (new \ReflectionClass($this))->getShortName(), $e->getMessage());

            throw new WebServiceException('Unable to contact WebService : '.$e->getMessage());
        }

        return $response;
    }

    private function getFromCache(Request $request)
    {
        $this->log->cacheGet($this->getHost(), $request, (new \ReflectionClass($this->cache))->getShortName());

        $response = $this->cache->getPool()->getItem($request);

        $this->log->response($this->getHost(), $response, $request);

        return $response;
    }

    private function saveToCache(Request $request, $response)
    {
        $duration = $this->cache->getConfig()->get($request, CacheHelperInterface::DEFAULT_DURATION);

        $item = $this->cache->getPool()
            ->getItem($request)
            ->set($response)
            ->expiresAfter($duration)
        ;

        $this->cache->getPool()->save($item);

        $this->log->cacheAdd($this->getHost(), $request, (new \ReflectionClass($this->cache))->getShortName(), $duration);
    }
}
