<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectProcessorFactory;
use Agit\CoreBundle\Pluggable\Strategy\Seed\SeedProcessorFactory;
use Agit\CoreBundle\Pluggable\Strategy\Seed\SeedData;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Combined\CombinedData;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectData;

/**
 * This processor internally combines the ObjectProcessor and the SeedProcessor.
 */
class CombinedProcessor implements ProcessorInterface
{
    private $objectProcessor;

    private $seedProcessorList;

    private $registrationTag;

    private $parentClass;

    private $entityNameList;

    public function __construct(
        ObjectProcessorFactory $objectProcessorFactory,
        SeedProcessorFactory $seedProcessorFactory,
        $registrationTag,
        $parentClass,
        array $entityNameList, // NOTE: order equals priority
        $seedDeleteObsolete = true,
        $seedUpdateExisting = true)
    {
        $this->objectProcessor = $objectProcessorFactory->create($registrationTag, $parentClass);
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
        $this->entityNameList = $entityNameList;
        $seedPriority = 0;

        foreach ($entityNameList as $entityName)
            $this->seedProcessorList[$entityName] = $seedProcessorFactory->create(
            $entityName,
            $seedPriority += 10,
            $seedDeleteObsolete,
            $seedUpdateExisting);
    }

    public function createRegistrationEvent()
    {
        return new CombinedRegistrationEvent($this);
    }

    public function getRegistrationTag()
    {
        return $this->registrationTag;
    }

    public function getParentClass()
    {
        return $this->parentClass;
    }

    public function getEntityNames()
    {
        return $this->entityNameList;
    }

    public function register(CombinedData $combinedData, $priority)
    {
        if (!class_exists($combinedData->getClass()))
            throw new InternalErrorException("Invalid class: " . $combinedData->getClass());

        $classRefl = new \ReflectionClass($combinedData->getClass());

        if (!$classRefl->isSubclassOf($this->parentClass))
            throw new InternalErrorException(sprintf("Class %s must be a subclass of %s.", $combinedData->getClass(), $this->parentClass));

        $objectData = new ObjectData();
        $objectData->setId($combinedData->getId());
        $objectData->setClass($combinedData->getClass());
        $this->objectProcessor->registerObject($objectData, $priority);

        foreach ($this->seedProcessorList as $entityName => $seedProcessor)
        {
            $seeds = $combinedData->getSeeds($entityName);

            foreach ($seeds as $entry)
            {
                $seedData = new SeedData();
                $seedData->setData($entry);
                $seedProcessor->register($seedData);
            }
        }
    }

    public function getPriority()
    {
        return 100;
    }

    public function process()
    {
        $this->objectProcessor->process();

        foreach ($this->seedProcessorList as $seedProcessor)
            $seedProcessor->process();
    }
}
