<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;
use Agit\PluggableBundle\Strategy\PluginInstanceFactoryTrait;

class CacheProcessor implements ProcessorInterface
{
    use PluginInstanceFactoryTrait;

    private $cacheProvider;

    private $container;

    private $baseClass;

    private $registrationTag;

    private $entryList = [];

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null, PluggableServiceInterface $pluggableService)
    {
        if (!($pluggableService instanceof CachePluggableService))
            throw new InternalErrorException("Pluggable service must be an instance of CachePluggableService.");

        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
        $this->registrationTag = $pluggableService->get("tag");
        $this->baseClass = $pluggableService->get("baseClass");
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        $instance = $this->createInstance($class, $plugin);
        $instance->load();

        while ($cacheEntry = $instance->nextCacheEntry())
            $this->entryList[$cacheEntry->getId()] = $cacheEntry->getData();
    }

    public function process()
    {
        $this->cacheProvider->save($this->registrationTag, $this->entryList);
    }

    protected function getContainer()
    {
        return $this->container;
    }

    protected function checkInterface($instance)
    {
        if (!($instance instanceof $this->baseClass))
            throw new InternalErrorException("The $class plugin must be a child of {$this->baseClass}.");

        if (!($instance instanceof CachePluginInterface))
            throw new InternalErrorException("The $class plugin must implement the CachePluginInterface.");
    }
}
