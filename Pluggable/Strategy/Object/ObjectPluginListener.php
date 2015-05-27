<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Agit\CoreBundle\Service\ClassCollector;
use Agit\CoreBundle\Exception\InternalErrorException;

/**
 * Reusable listener that collects plugin objects from a given path. "Reusable"
 * means that you can use an instance of this listener as service, without the
 * need of creating a derived class or own implementation.
 */
class ObjectPluginListener
{
    private $ClassCollector;

    protected $searchPath;

    private $priority;

    public function __construct($ClassCollector, $searchPath, $priority)
    {
        $this->ClassCollector = $ClassCollector;
        $this->searchPath = $searchPath;
        $this->priority = $priority;
    }

    /**
     * the event listener to be used in the service configuration
     */
    public function onRegistration(ObjectRegistrationEvent $RegistrationEvent)
    {
        foreach ($this->ClassCollector->collect($this->searchPath) as $class)
        {
            $ClassRefl = new \ReflectionClass($class);
            $parentClass = $RegistrationEvent->getParentClass();
            $object = new $class();

            if ($ClassRefl->isAbstract() || !($object instanceof PluginObjectInterface) || !$ClassRefl->isSubclassOf($parentClass))
                continue;

            $ObjectData = $RegistrationEvent->createContainer();
            $ObjectData->setId($object->getId());
            $ObjectData->setClass($class);
            $RegistrationEvent->register($ObjectData, $this->priority);
        }
    }
}
