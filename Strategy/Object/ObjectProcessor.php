<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Object;

use Doctrine\Common\Cache\CacheProvider;
use Agit\BaseBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\Object\ObjectPluggableService;
use Agit\PluggableBundle\Strategy\Object\ObjectPlugin;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

class ObjectProcessor implements ProcessorInterface
{
    private $baseClass;

    private $plugins = [];

    public function __construct(CacheProvider $cacheProvider, PluggableServiceInterface $pluggableService)
    {
        if (!($pluggableService instanceof ObjectPluggableService))
            throw new InternalErrorException("Pluggable $class must be an instance of ObjectPluggableService.");

        $this->cacheProvider = $cacheProvider;
        $this->registrationTag = $pluggableService->get('tag');
        $this->baseClass = $pluggableService->get('baseClass');
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        if (!is_string($class) || !class_exists($class))
            throw new InternalErrorException("Invalid plugin class.");

        $classRefl = new \ReflectionClass($class);

        if (!$classRefl->isSubclassOf($this->baseClass))
            throw new InternalErrorException(sprintf("%s must be a subclass of %s.", $class, $this->baseClass));

        if (!($plugin instanceof ObjectPlugin))
            throw new InternalErrorException("Plugin $class must be an instance of ObjectPlugin.");

        $id = $plugin->get('id') ?: $class;
        $this->plugins[$id] = ['meta' => $plugin, 'class' => $class];
    }

    public function process()
    {
        $this->cacheProvider->save($this->registrationTag, $this->plugins);
    }
}
