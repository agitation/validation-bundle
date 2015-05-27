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

    private $ObjectList;

    private $objectFactoryCallback;

    private $Container;

    public function __construct(CacheProvider $CacheProvider, $registrationTag, ContainerInterface $Container = null)
    {
        parent::__construct($CacheProvider, $registrationTag);

        // NOTE: The container is only set if the pluggable service
        // allows dependency injection.
        $this->Container = $Container;
    }

    public function setObjectFactory($callback)
    {
        $this->objectFactoryCallback = $callback;
    }

    public function getObject($id)
    {
        $this->loadObject($id);
        return $this->ObjectList[$id]['instance'];
    }

    public function getAllObjects()
    {
        $list = [];
        $this->loadObjectList();

        foreach ($this->ObjectList as $id => $data)
        {
            if (!isset($data['instance']))
                $this->loadObject($id);

            $list[] = $this->ObjectList[$id]['instance'];
        }

        return $list;
    }

    protected function loadObject($id)
    {
        $this->loadObjectList();

        if (!isset($this->ObjectList[$id]))
            throw new InternalErrorException("An object with the identifier '$id' has not been registered.");

        if (!isset($this->ObjectList[$id]['instance']))
        {
            $objectFactoryCallback = $this->objectFactoryCallback ?: [$this, 'objectFactory'];
            $instance = call_user_func($objectFactoryCallback, $id, $this->ObjectList[$id]['class']);

            if ($this->Container && in_array(__NAMESPACE__ . '\ServiceAwarePlugin', class_uses($instance)))
                foreach ($instance->getServiceDependencies() as $serviceName)
                    $instance->setService($serviceName, $this->Container->get($serviceName));

            $this->ObjectList[$id]['instance'] = $instance;
        }
    }

    protected function loadObjectList()
    {
        if (is_null($this->ObjectList))
        {
            $this->ObjectList = [];

            foreach ($this->loadPlugins() as $id => $class)
                $this->ObjectList[$id] = ['class' => $class, 'instance' => null];
        }
    }

    protected function objectFactory($id, $class)
    {
        return new $class();
    }
}
