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
use Agit\BaseBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ServiceInjectorTrait;

class ObjectLoader
{
    use ServiceInjectorTrait;

    protected $objects;

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
        return $this->objects[$id]['instance'];
    }

    public function getAllObjects()
    {
        $list = [];
        $this->loadObjects();

        foreach ($this->objects as $id => $data)
        {
            if (!isset($data['instance']))
                $this->loadObject($id);

            $list[] = $this->objects[$id]['instance'];
        }

        return $list;
    }

    protected function loadObject($id)
    {
        $this->loadObjects();

        if (!isset($this->objects[$id]))
            throw new InternalErrorException("An object with the identifier '$id' has not been registered.");

        if (!isset($this->objects[$id]['instance']))
        {
            $class = $this->objects[$id]['class'];
            $instance = new $class();
            $dependencies = $this->objects[$id]['meta']->get('depends');

            $this->injectServices($instance, $dependencies);
            $this->objects[$id]['instance'] = $instance;
        }
    }

    protected function loadObjects()
    {
        if (is_null($this->objects))
        {
            $this->objects = [];

            $plugins = $this->cacheProvider->fetch($this->registrationTag) ?: [];

            foreach ($plugins as $id => $data)
                $this->objects[$id] = ['meta' => $data['meta'], 'class' => $data['class'], 'instance' => null];
        }
    }
}
