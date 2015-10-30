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
    private $entityManager;

    private $entityValidator;

    private $metadata;

    private $entityName;

    private $priority;

    private $removeObsolete;

    private $updateExisting;

    private $seedDataList = [];

    public function __construct(EntityManager $entityManager, Validator $entityValidator, $entityName, $priority, $removeObsolete = true, $updateExisting = true)
    {
        $this->entityManager = $entityManager;
        $this->entityValidator = $entityValidator;

        $this->entityName = $entityName;
        $this->priority = (int)$priority;
        $this->removeObsolete = (bool)$removeObsolete;
        $this->updateExisting = (bool)$updateExisting;

        $this->metadata = $this->entityManager->getClassMetadata($this->entityName);
    }

    public function createRegistrationEvent()
    {
        return new SeedRegistrationEvent($this);
    }

    public function getRegistrationTag()
    {
        return $this->entityName;
    }

    public function register(SeedData $seedData)
    {
        $this->seedDataList[] = $seedData;
    }

    public function process()
    {
        $idField = $this->getIdField();
        $entityClass = $this->metadata->getName();
        $entityList = $this->getExistingObjects($idField);

        foreach ($this->seedDataList as $seedData)
        {
            $entry = $seedData->getData();

            if (!isset($entry[$idField]))
                throw new InternalErrorException("The seed data for $entityClass is missing the mandatory '$idField' field.");

            if (isset($entityList[$entry[$idField]]))
            {
                $entity = $entityList[$entry[$idField]];
                unset($entityList[$entry[$idField]]);

                if (!$this->updateExisting) continue;
            }
            else
            {
                $entity = new $entityClass();
            }

            foreach ($entry as $key => $value)
                $this->setObjectValue($entity, $key, $value);

            $this->entityValidator->validate($entity);
            $this->entityManager->persist($entity);
        }

        if ($this->removeObsolete)
            $this->removeObsoleteObjects($entityList);

        $this->entityManager->flush();
    }

    public function getPriority()
    {
        return $this->priority;
    }

    private function getExistingObjects($idField)
    {
        $entityList = [];

        foreach ($this->entityManager->getRepository($this->entityName)->findAll() as $entity)
            $entityList[$this->metadata->getFieldValue($entity, $idField)] = $entity;

        return $entityList;
    }

    private function getIdField()
    {
        $idFieldList = $this->metadata->getIdentifier();

        if (!is_array($idFieldList) || count($idFieldList) !== 1)
            throw new InternalErrorException("Seed entities must have exactly one ID field.");

        return reset($idFieldList);
    }

    private function setObjectValue($entity, $key, $value)
    {
        if (isset($this->metadata->associationMappings[$key]))
            $value = $this->entityManager
                ->getReference($this->metadata->associationMappings[$key]['targetEntity'], $value);

        $this->metadata->setFieldValue($entity, $key, $value);
    }

    private function removeObsoleteObjects($entityList)
    {
        foreach ($entityList as $entity)
            $this->entityManager->remove($entity);
    }
}
