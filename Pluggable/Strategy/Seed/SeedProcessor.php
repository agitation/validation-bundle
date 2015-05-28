<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Seed;

use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator;

/**
 * Processes registered objects
 */
class SeedProcessor implements ProcessorInterface
{
    private $EntityManager;

    private $EntityValidator;

    private $Metadata;

    private $entityName;

    private $priority;

    private $removeObsolete;

    private $updateExisting;

    private $SeedDataList = [];

    public function __construct(EntityManager $EntityManager, Validator $EntityValidator, $entityName, $priority, $removeObsolete = true, $updateExisting = true)
    {
        $this->EntityManager = $EntityManager;
        $this->EntityValidator = $EntityValidator;

        $this->entityName = $entityName;
        $this->priority = (int)$priority;
        $this->removeObsolete = (bool)$removeObsolete;
        $this->updateExisting = (bool)$updateExisting;

        $this->Metadata = $this->EntityManager->getClassMetadata($this->entityName);
    }

    public function createRegistrationEvent()
    {
        return new SeedRegistrationEvent($this);
    }

    public function getRegistrationTag()
    {
        return $this->entityName;
    }

    public function register(SeedData $SeedData)
    {
        $this->SeedDataList[] = $SeedData;
    }

    public function process()
    {
        $idField = $this->getIdField();
        $entityClass = $this->Metadata->getName();
        $EntityList = $this->getExistingObjects($idField);

        foreach ($this->SeedDataList as $SeedData)
        {
            $entry = $SeedData->getData();

            if (!isset($entry[$idField]))
                throw new InternalErrorException("The seed data for $entityClass is missing the mandatory '$idField' field.");

            if (isset($EntityList[$entry[$idField]]))
            {
                $Entity = $EntityList[$entry[$idField]];
                unset($EntityList[$entry[$idField]]);

                if (!$this->updateExisting) continue;
            }
            else
            {
                $Entity = new $entityClass();
            }

            foreach ($entry as $key => $value)
                $this->setObjectValue($Entity, $key, $value);

            $this->EntityValidator->validate($Entity);
            $this->EntityManager->persist($Entity);
        }

        if ($this->removeObsolete)
            $this->removeObsoleteObjects($EntityList);

        $this->EntityManager->flush();
    }

    public function getPriority()
    {
        return $this->priority;
    }

    private function getExistingObjects($idField)
    {
        $EntityList = [];

        foreach ($this->EntityManager->getRepository($this->entityName)->findAll() as $Entity)
            $EntityList[$this->Metadata->getFieldValue($Entity, $idField)] = $Entity;

        return $EntityList;
    }

    private function getIdField()
    {
        if ($this->Metadata->usesIdGenerator())
            throw new InternalErrorException("Seed entities must not use an ID generator.");

        $idFieldList = $this->Metadata->getIdentifier();

        if (!is_array($idFieldList) || count($idFieldList) !== 1)
            throw new InternalErrorException("Seed entities must have exactly one ID field.");

        return reset($idFieldList);
    }

    private function setObjectValue($Entity, $key, $value)
    {
        if (isset($this->Metadata->associationMappings[$key]))
            $value = $this->EntityManager
                ->getReference($this->Metadata->associationMappings[$key]['targetEntity'], $value);

        $this->Metadata->setFieldValue($Entity, $key, $value);
    }

    private function removeObsoleteObjects($EntityList)
    {
        foreach ($EntityList as $Entity)
            $this->EntityManager->remove($Entity);
    }
}
