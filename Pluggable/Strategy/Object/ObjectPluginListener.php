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
    private $classCollector;

    protected $searchPath;

    private $priority;

    public function __construct($classCollector, $searchPath, $priority)
    {
        $this->classCollector = $classCollector;
        $this->searchPath = $searchPath;
        $this->priority = $priority;
    }

    /**
     * the event listener to be used in the service configuration
     */
    public function onRegistration(ObjectRegistrationEvent $registrationEvent)
    {
        foreach ($this->classCollector->collect($this->searchPath) as $class)
        {
            $classRefl = new \ReflectionClass($class);
            $parentClass = $registrationEvent->getParentClass();
            $object = new $class();

            if ($classRefl->isAbstract() || !($object instanceof PluginObjectInterface) || !$classRefl->isSubclassOf($parentClass))
                continue;

            $objectData = $registrationEvent->createContainer();
            $objectData->setId($object->getId());
            $objectData->setClass($class);
            $registrationEvent->register($objectData, $this->priority);
        }
    }
}
