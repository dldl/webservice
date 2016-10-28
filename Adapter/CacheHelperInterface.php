<?php

namespace dLdL\WebService\Adapter;

use Psr\Cache\CacheItemPoolInterface;

/**
 * CacheHelperInterface is using a PSR-6 cache system and configuration
 * stored in a ParameterBag.
 */
interface CacheHelperInterface
{
    const DEFAULT_DURATION = 'default';

    /**
     * Get the PSR-6 compliant CacheItemPool.
     *
     * @return CacheItemPoolInterface
     */
    public function getPool();

    /**
     * Get the configuration ParameterBag with configured cache durations
     * for requests and/or default duration.
     *
     * @return ParameterBagInterface
     */
    public function getConfig();
}
