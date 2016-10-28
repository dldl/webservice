<?php

namespace dLdL\WebService\Adapter;

use Psr\Cache\CacheItemPoolInterface;

class CacheHelper implements CacheHelperInterface
{
    private $pool;
    private $parameterBag;

    public function __construct(CacheItemPoolInterface $pool, ParameterBagInterface $parameterBag)
    {
        $this->pool = $parameterBag;
        $this->parameterBag = $parameterBag;
    }

    public function getPool()
    {
        return $this->pool;
    }

    public function getConfig()
    {
        return $this->parameterBag;
    }
}
