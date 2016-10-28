<?php

namespace dLdL\WebService;

use dLdL\WebService\Adapter\CacheHelperInterface;
use dLdL\WebService\Exception\ConnectionException;
use dLdL\WebService\Adapter\ParameterBag;
use Psr\Log\LoggerInterface;
use dLdL\WebService\Http\Request;

/**
 * AdapterInterface defines basic rules to connect to a WebService.
 */
interface AdapterInterface
{
    /**
     * Initialize the adapter.
     *
     * @param string $host Host of the webservice
     *
     * @throws ConnectionException if connection fails
     */
    public function connect($host);

    /**
     * Get the current adapter host.
     *
     * @throws ConnectionException if adapter is not connected
     *
     * @see AdapterInterface::isConnected() to check if adapter is connected
     */
    public function getHost();

    /**
     * Check if the adapter is initialized.
     *
     * @return bool true if this adapter can handle requests
     */
    public function isConnected();

    /**
     * Disconnect the adapter and free resources.
     */
    public function disconnect();

    /**
     * Check if the interface supports a specific request method.
     *
     * @param string $method The method to be checked, for instance : 'GET', 'POST', ...
     *
     * @return bool true if the method can be handled
     */
    public function supportsMethod($method);

    /**
     * Send a request to the WebService.
     *
     * @param Request $request The request to be send
     *
     * @return mixed The raw request answer
     */
    public function sendRequest(Request $request);

    /**
     * Get the parameters defined for the adapter.
     *
     * @return ParameterBag The ParameterBag with defined parameters
     */
    public function getParameters();

    /**
     * Set or remove a cache handler.
     *
     * @param CacheHelperInterface|null $cache The cache handler to be set or null to remove the current cache handler
     */
    public function setCache(CacheHelperInterface $cache = null);

    /**
     * Check if a cache handler is defined for the adapter.
     *
     * @return bool
     */
    public function hasCache();

    /**
     * Get the current configured cache.
     *
     * @return CacheHelperInterface
     */
    public function getCache();

    /**
     * Set a logger to log WebServices access.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);
}
