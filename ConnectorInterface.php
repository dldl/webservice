<?php

namespace dLdL\WebService;

use dLdL\WebService\Adapter\CacheHelperInterface;
use dLdL\WebService\Exception\ConnectionException;
use dLdL\WebService\Adapter\ParameterBag;
use Psr\Log\LoggerInterface;
use dLdL\WebService\Http\Request;

/**
 * ConnectorInterface defines rules to connect to a WebService.
 */
interface ConnectorInterface
{
    /**
     * Initialize the connector and the connection to the host if needed.
     *
     * @param string $host Host of the webservice
     *
     * @throws ConnectionException if connection fails
     */
    public function connect($host);

    /**
     * Get the current connector host.
     *
     * @throws ConnectionException if connector is not connected and initialized
     *
     * @see ConnectorInterface::isConnected() to check if connector is connected
     *
     * @return string The current hostname
     */
    public function getHost();

    /**
     * Check if the connector is initialized and connected if needed.
     *
     * @return bool true if this connector can handle requests
     */
    public function isConnected();

    /**
     * Disconnect the connector and free resources.
     */
    public function disconnect();

    /**
     * Check if the connector supports a specific request method.
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
     * Get the defined parameters of the connector.
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
     * Check if a cache handler is defined for the parameters.
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
