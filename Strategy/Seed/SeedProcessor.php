<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Seed;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;
use Agit\PluggableBundle\Strategy\PluginInstanceFactoryTrait;

class SeedProcessor implements ProcessorInterface
{
    use PluginInstanceFactoryTrait;

    private $entityManager;

    private $container;

    private $registrationTag;

    private $entities = [];

    public function __construct(
        EntityManager $entityManager,
        ContainerInterface $container = null,
        PluggableServiceInterface $pluggableService
    )
    {
        if (!($pluggableService instanceof SeedPluggableService))
            throw new InternalErrorException("Pluggable service must be an instance of SeedPluggableService.");

        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        $instance = $this->createInstance($class, $plugin);
        $instance->load();
        $entityName = $plugin->get("entity");

        while ($seedEntry = $instance->nextSeedEntry())
        {
            if (!isset($this->entities[$entityName]))
                $this->entities[$entityName] = [];

            $this->entities[$entityName][] = $seedEntry;
        }
    }

    protected function checkInterface($instance)
    {
        if (!($instance instanceof SeedPluginInterface))
            throw new InternalErrorException("The $class plugin must implement the SeedPluginInterface.");
    }

    protected function getContainer()
    {
        return $this->container;
    }

    public function process()
    {
        $entityClasses = $this->entityManager->getConfiguration()
            ->getMetadataDriverImpl()->getAllClassNames();

        foreach ($this->entities as $entityName => $seedEntries)
        {
            $metadata = $this->entityManager->getClassMetadata($entityName);

            // we need to know now if the entity usually has a generator as we will overwrite the generator below
            $usesIdGenerator = $metadata->usesIdGenerator();

            $idField = $this->getIdField($metadata);
            $entityClass = $metadata->getName();

            // it may be that a component ships (optional) entries for another component that is not installed
            if (!in_array($entityClass, $entityClasses))
                continue;

            $entities = $this->getExistingObjects($entityName, $idField, $metadata);

            foreach ($seedEntries as $seedEntry)
            {
                $data = $seedEntry->getData();

                if (!isset($data[$idField]))
                    throw new InternalErrorException("The seed data for $entityClass is missing the mandatory `$idField` field.");

                if (isset($entities[$data[$idField]]))
                {
                    $entity = $entities[$data[$idField]];
                    unset($entities[$data[$idField]]);

                    if (!$seedEntry->doUpdate()) continue;
                }
                else
                {
                    $entity = new $entityClass();
                }

                foreach ($data as $key => $value)
                    $this->setObjectValue($entity, $key, $value, $metadata);

                $this->entityManager->persist($entity);

                // we overwrite the ID generator here, because we ALWAYS need fixed IDs
                $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
            }

            // remove old entries, but only for entities with natural keys
            if (!$usesIdGenerator)
                $this->removeObsoleteObjects($entities);
        }

        $this->entityManager->flush();
    }

    private function getExistingObjects($entityName, $idField, $metadata)
    {
        $entities = [];

        foreach ($this->entityManager->getRepository($entityName)->findAll() as $entity)
            $entities[$metadata->getFieldValue($entity, $idField)] = $entity;

        return $entities;
    }

    private function getIdField($metadata)
    {
        $idFields = $metadata->getIdentifier();

        if (!is_array($idFields) || count($idFields) !== 1)
            throw new InternalErrorException("Seed entities must have exactly one ID field.");

        return reset($idFields);
    }

    private function setObjectValue($entity, $key, $value, $metadata)
    {
        if ($value && isset($metadata->associationMappings[$key]))
        {
            $mapping = $metadata->getAssociationMapping($key);
            $targetEntity = $metadata->associationMappings[$key]["targetEntity"];

            if ($mapping["type"] & ClassMetadataInfo::TO_MANY)
            {
                $collection = $metadata->getFieldValue($entity, $key);

                foreach ($value as $childId)
                    $collection->add($this->entityManager->getReference($targetEntity, $childId));

                $value = $collection;
            }
            else
            {
                $value = $this->entityManager->getReference($targetEntity, $value);
            }
        }

        $metadata->setFieldValue($entity, $key, $value);
    }

    private function removeObsoleteObjects($entities)
    {
        foreach ($entities as $entity)
            $this->entityManager->remove($entity);
    }
}
