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
    public function onRegistration(CombinedRegistrationEvent $RegistrationEvent)
    {
        foreach ($this->ClassCollector->collect($this->searchPath) as $class)
        {
            $ClassRefl = new \ReflectionClass($class);

            if ($ClassRefl->isAbstract())
                continue;

            $parentClass = $RegistrationEvent->getParentClass();
            $entityNameList = $RegistrationEvent->getEntityNames();

            if (!$ClassRefl->isSubclassOf('Agit\CoreBundle\Pluggable\Strategy\Combined\CombinedPluginInterface'))
                continue;

            if (!$ClassRefl->isSubclassOf($parentClass))
                throw new InternalErrorException(sprintf("$class must be a child of %s.", $parentClass));

            $CombinedData = $RegistrationEvent->createContainer();
            $CombinedData->setClass($class);
            $CombinedData->setId($class::getPluginId());

            foreach ($entityNameList as $entityName)
                $CombinedData->setFixtures($entityName, $class::getFixtures($entityName));

            $RegistrationEvent->register($CombinedData, $this->priority);
        }
    }
}
