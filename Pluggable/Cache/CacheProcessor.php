<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Cache;

use Agit\BaseBundle\Exception\InternalErrorException;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\PluginInstanceFactoryTrait;
use Agit\BaseBundle\Pluggable\PluginInterface;
use Agit\BaseBundle\Pluggable\ProcessorInterface;
use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CacheProcessor implements ProcessorInterface
{
    use PluginInstanceFactoryTrait;

    private $cacheProvider;

    private $container;

    private $baseClass;

    private $registrationTag;

    private $entries = [];

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null, PluggableServiceInterface $pluggableService)
    {
        if (! ($pluggableService instanceof CachePluggableService)) {
            throw new InternalErrorException("Pluggable service must be an instance of CachePluggableService.");
        }

        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
        $this->registrationTag = $pluggableService->get("tag");
        $this->baseClass = $pluggableService->get("baseClass");
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        $instance = $this->createInstance($class, $plugin);
        $instance->load();

        while ($cacheEntry = $instance->nextCacheEntry()) {
            $this->entries[$cacheEntry->getId()] = $cacheEntry->getData();
        }
    }

    public function process()
    {
        $this->cacheProvider->save($this->registrationTag, $this->entries);
    }

    protected function getContainer()
    {
        return $this->container;
    }

    protected function checkInterface($instance)
    {
        if (! ($instance instanceof $this->baseClass)) {
            throw new InternalErrorException("The $class plugin must be a child of {$this->baseClass}.");
        }

        if (! ($instance instanceof CachePluginInterface)) {
            throw new InternalErrorException("The $class plugin must implement the CachePluginInterface.");
        }
    }
}
