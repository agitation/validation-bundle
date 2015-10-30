<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\CacheProvider;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheLoader;
/**
 * Provides registered objects to the pluggable service at run-time.
 */
class ObjectLoader extends CacheLoader
{
    private $plugins;

    private $objectList;

    private $objectFactoryCallback;

    private $container;

    public function __construct(CacheProvider $cacheProvider, $registrationTag, ContainerInterface $container = null)
    {
        parent::__construct($cacheProvider, $registrationTag);

        // If the ObjectLoader should do DI, the factory must provide
        // the container.
        $this->container = $container;
    }

    public function setObjectFactory($callback)
    {
        $this->objectFactoryCallback = $callback;
    }

    public function getObject($id)
    {
        $this->loadObject($id);
        return $this->objectList[$id]['instance'];
    }

    public function getAllObjects()
    {
        $list = [];
        $this->loadObjectList();

        foreach ($this->objectList as $id => $data)
        {
            if (!isset($data['instance']))
                $this->loadObject($id);

            $list[] = $this->objectList[$id]['instance'];
        }

        return $list;
    }

    protected function loadObject($id)
    {
        $this->loadObjectList();

        if (!isset($this->objectList[$id]))
            throw new InternalErrorException("An object with the identifier '$id' has not been registered.");

        if (!isset($this->objectList[$id]['instance']))
        {
            $objectFactoryCallback = $this->objectFactoryCallback ?: [$this, 'objectFactory'];
            $instance = call_user_func($objectFactoryCallback, $id, $this->objectList[$id]['class']);

            if ($this->container && in_array(__NAMESPACE__ . '\ServiceAwarePlugin', class_uses($instance)))
                foreach ($instance->getServiceDependencies() as $serviceName)
                    $instance->setService($serviceName, $this->container->get($serviceName));

            $this->objectList[$id]['instance'] = $instance;
        }
    }

    protected function loadObjectList()
    {
        if (is_null($this->objectList))
        {
            $this->objectList = [];

            foreach ($this->loadPlugins() as $id => $class)
                $this->objectList[$id] = ['class' => $class, 'instance' => null];
        }
    }

    protected function objectFactory($id, $class)
    {
        return new $class();
    }
}
