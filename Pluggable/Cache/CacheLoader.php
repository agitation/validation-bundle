<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Doctrine\Common\Cache\CacheProvider;

class CacheLoader
{
    private $cacheProvider;

    private $registrationTag;

    public function __construct(CacheProvider $cacheProvider, $registrationTag)
    {
        $this->cacheProvider = $cacheProvider;
        $this->registrationTag = $registrationTag;
    }

    public function load()
    {
        return $this->cacheProvider->fetch($this->registrationTag) ?: [];
    }
}
