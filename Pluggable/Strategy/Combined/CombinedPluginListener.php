<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Service\ClassCollector;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Combined\CombinedPluginInterface;

class CombinedPluginListener
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
    public function onRegistration(CombinedRegistrationEvent $registrationEvent)
    {
        foreach ($this->classCollector->collect($this->searchPath) as $class)
        {
            $classRefl = new \ReflectionClass($class);

            if ($classRefl->isAbstract())
                continue;

            $parentClass = $registrationEvent->getParentClass();
            $entityNameList = $registrationEvent->getEntityNames();

            if (!$classRefl->isSubclassOf('Agit\CoreBundle\Pluggable\Strategy\Combined\CombinedPluginInterface'))
                continue;

            if (!$classRefl->isSubclassOf($parentClass))
                continue;

            $combinedData = $registrationEvent->createContainer();
            $combinedData->setClass($class);
            $combinedData->setId($class::getPluginId());

            foreach ($entityNameList as $entityName)
                $combinedData->setSeeds($entityName, $class::getSeeds($entityName));

            $registrationEvent->register($combinedData, $this->priority);
        }
    }
}
