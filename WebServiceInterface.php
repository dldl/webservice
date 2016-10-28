<?php

namespace dLdL\WebService;

/**
 * WebServiceInterface encapsulates an adapter that can be used
 * by any WebService connector to get data from a web service.
 */
interface WebServiceInterface
{
    /**
     * Get the adapter used by the connector to contact the WebService.
     *
     * @return AdapterInterface
     */
    public function getAdapter();
}
