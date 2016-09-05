<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Object;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ObjectLoaderFactory
{
    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
    }

    public function create($registrationTag)
    {
        return new ObjectLoader($this->cacheProvider, $this->container, $registrationTag);
    }
}
