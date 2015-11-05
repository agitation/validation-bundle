<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Object;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Cache\CacheProvider;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;

class ObjectLoader
{
    protected $objectList;

    private $cacheProvider;

    private $container;

    private $registrationTag;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null, $registrationTag)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
        $this->registrationTag = $registrationTag;
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
            $class = $this->objectList[$id]['class'];
            $instance = new $class();
            $dependencies = $this->objectList[$id]['meta']->get('depends');

            if (is_array($dependencies) && count($dependencies))
            {
                if (!$this->container)
                    throw new InternalErrorException("The $class plugin needs the services container.");

                if (!($instance instanceof ServiceAwarePluginInterface))
                    throw new InternalErrorException("The $class plugin has defined dependencies and thus must implement the ServiceAwarePluginInterface.");

                foreach ($dependencies as $serviceName)
                    $instance->setService($serviceName, $this->container->get($serviceName));
            }

            $this->objectList[$id]['instance'] = $instance;
        }
    }

    protected function loadObjectList()
    {
        if (is_null($this->objectList))
        {
            $this->objectList = [];

            $plugins = $this->cacheProvider->fetch($this->registrationTag) ?: [];

            foreach ($plugins as $id => $data)
                $this->objectList[$id] = ['meta' => $data['meta'], 'class' => $data['class'], 'instance' => null];
        }
    }
}
