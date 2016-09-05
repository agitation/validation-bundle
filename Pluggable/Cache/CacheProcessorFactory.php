<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\ProcessorFactoryInterface;
use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Processes registered objects.
 */
class CacheProcessorFactory implements ProcessorFactoryInterface
{
    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
    }

    public function createPluggableService(array $attributes)
    {
        return new CachePluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new CacheProcessor($this->cacheProvider, $this->container, $pluggableService);
    }
}
