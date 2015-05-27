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
use Agit\CoreBundle\Pluggable\Strategy\Fixture\FixtureProcessorFactory;
use Agit\CoreBundle\Pluggable\Strategy\Fixture\FixtureData;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\Combined\CombinedData;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectData;

/**
 * This processor internally combines the ObjectProcessor and the FixtureProcessor.
 */
class CombinedProcessor implements ProcessorInterface
{
    private $ObjectProcessor;

    private $FixtureProcessorList;

    private $registrationTag;

    private $parentClass;

    private $entityNameList;

    public function __construct(
        ObjectProcessorFactory $ObjectProcessorFactory,
        FixtureProcessorFactory $FixtureProcessorFactory,
        $registrationTag,
        $parentClass,
        array $entityNameList, // NOTE: order equals priority
        $fixtureDeleteObsolete = true,
        $fixtureUpdateExisting = true)
    {
        $this->ObjectProcessor = $ObjectProcessorFactory->create($registrationTag, $parentClass);
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
        $this->entityNameList = $entityNameList;
        $fixturePriority = 0;

        foreach ($entityNameList as $entityName)
            $this->FixtureProcessorList[$entityName] = $FixtureProcessorFactory->create(
            $entityName,
            $fixturePriority += 10,
            $fixtureDeleteObsolete,
            $fixtureUpdateExisting);
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

    public function register(CombinedData $CombinedData, $priority)
    {
        if (!class_exists($CombinedData->getClass()))
            throw new InternalErrorException("Invalid class: " . $CombinedData->getClass());

        $ClassRefl = new \ReflectionClass($CombinedData->getClass());

        if (!$ClassRefl->isSubclassOf($this->parentClass))
            throw new InternalErrorException(sprintf("Class %s must be a subclass of %s.", $CombinedData->getClass(), $this->parentClass));

        $ObjectData = new ObjectData();
        $ObjectData->setId($CombinedData->getId());
        $ObjectData->setClass($CombinedData->getClass());
        $this->ObjectProcessor->registerObject($ObjectData, $priority);

        foreach ($this->FixtureProcessorList as $entityName => $FixtureProcessor)
        {
            $fixtures = $CombinedData->getFixtures($entityName);

            foreach ($fixtures as $entry)
            {
                $FixtureData = new FixtureData();
                $FixtureData->setData($entry);
                $FixtureProcessor->register($FixtureData);
            }
        }
    }

    public function getPriority()
    {
        return 100;
    }

    public function process()
    {
        $this->ObjectProcessor->process();

        foreach ($this->FixtureProcessorList as $FixtureProcessor)
            $FixtureProcessor->process();
    }
}
