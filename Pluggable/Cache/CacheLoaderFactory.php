<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Doctrine\Common\Cache\CacheProvider;

class CacheLoaderFactory
{
    private $cacheProvider;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function create($registrationTag)
    {
        return new CacheLoader($this->cacheProvider, $registrationTag);
    }
}
