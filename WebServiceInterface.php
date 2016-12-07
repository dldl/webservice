<?php

namespace dLdL\WebService;

/**
 * WebServiceInterface encapsulates a connector that can be used
 * by any WebService class to get data from a web service.
 */
interface WebServiceInterface
{
    /**
     * Get the connector used by the WebService class to contact the WebService.
     *
     * @return ConnectorInterface
     */
    public function getConnector();
}
