<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Object;

use Agit\BaseBundle\Exception\InternalErrorException;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\PluginInterface;
use Agit\BaseBundle\Pluggable\ProcessorInterface;
use Doctrine\Common\Cache\CacheProvider;
use ReflectionClass;

class ObjectProcessor implements ProcessorInterface
{
    private $baseClass;

    private $plugins = [];

    public function __construct(CacheProvider $cacheProvider, PluggableServiceInterface $pluggableService)
    {
        if (! ($pluggableService instanceof ObjectPluggableService)) {
            throw new InternalErrorException("Pluggable $class must be an instance of ObjectPluggableService.");
        }

        $this->cacheProvider = $cacheProvider;
        $this->registrationTag = $pluggableService->get('tag');
        $this->baseClass = $pluggableService->get('baseClass');
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        if (! is_string($class) || ! class_exists($class)) {
            throw new InternalErrorException("Invalid plugin class.");
        }

        $classRefl = new ReflectionClass($class);

        if (! $classRefl->isSubclassOf($this->baseClass)) {
            throw new InternalErrorException(sprintf("%s must be a subclass of %s.", $class, $this->baseClass));
        }

        if (! ($plugin instanceof ObjectPlugin)) {
            throw new InternalErrorException("Plugin $class must be an instance of ObjectPlugin.");
        }

        $id = $plugin->get('id') ?: $class;
        $this->plugins[$id] = ['meta' => $plugin, 'class' => $class];
    }

    public function process()
    {
        $this->cacheProvider->save($this->registrationTag, $this->plugins);
    }
}
