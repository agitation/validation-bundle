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
use Symfony\Component\Validator\Validator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\PluginInterface;
use Agit\PluggableBundle\Strategy\ServiceAwarePluginInterface;
use Agit\PluggableBundle\Strategy\PluginInstanceFactoryTrait;

class SeedProcessor implements ProcessorInterface
{
    use PluginInstanceFactoryTrait;

    private $entityManager;

    private $entityValidator;

    private $container;

    private $registrationTag;

    private $entityList = [];

    public function __construct(
        EntityManager $entityManager,
        Validator $entityValidator,
        ContainerInterface $container = null,
        PluggableServiceInterface $pluggableService
    )
    {
        if (!($pluggableService instanceof SeedPluggableService))
            throw new InternalErrorException("Pluggable service must be an instance of SeedPluggableService.");

        $this->entityManager = $entityManager;
        $this->entityValidator = $entityValidator;
        $this->container = $container;
    }

    public function addPlugin($class, PluginInterface $plugin)
    {
        $instance = $this->createInstance($class, $plugin);
        $instance->load();
        $entityName = $plugin->get('entity');

        while ($seedEntry = $instance->nextSeedEntry())
        {
            if (!isset($this->entityList[$entityName]))
                $this->entityList[$entityName] = [];

            $this->entityList[$entityName][] = $seedEntry;
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

        foreach ($this->entityList as $entityName => $seedEntryList)
        {
            $metadata = $this->entityManager->getClassMetadata($entityName);
            $idField = $this->getIdField($metadata);
            $entityClass = $metadata->getName();

            // it may be that a component ships (optional) entries for another component that is not installed
            if (!in_array($entityClass, $entityClasses))
                continue;

            $entityList = $this->getExistingObjects($entityName, $idField, $metadata);

            foreach ($seedEntryList as $seedEntry)
            {
                $data = $seedEntry->getData();

                if (!isset($data[$idField]))
                    throw new InternalErrorException("The seed data for $entityClass is missing the mandatory '$idField' field.");

                if (isset($entityList[$data[$idField]]))
                {
                    $entity = $entityList[$data[$idField]];
                    unset($entityList[$data[$idField]]);

                    if (!$seedEntry->doUpdate()) continue;
                }
                else
                {
                    $entity = new $entityClass();
                }

                foreach ($data as $key => $value)
                    $this->setObjectValue($entity, $key, $value, $metadata);

                $this->entityValidator->validate($entity);
                $this->entityManager->persist($entity);
            }

            // remove old entries, but only for entities with natural keys
            if (!$metadata->usesIdGenerator())
                $this->removeObsoleteObjects($entityList);
        }

        $this->entityManager->flush();
    }

    private function getExistingObjects($entityName, $idField, $metadata)
    {
        $entityList = [];

        foreach ($this->entityManager->getRepository($entityName)->findAll() as $entity)
            $entityList[$metadata->getFieldValue($entity, $idField)] = $entity;

        return $entityList;
    }

    private function getIdField($metadata)
    {
        $idFieldList = $metadata->getIdentifier();

        if (!is_array($idFieldList) || count($idFieldList) !== 1)
            throw new InternalErrorException("Seed entities must have exactly one ID field.");

        return reset($idFieldList);
    }

    private function setObjectValue($entity, $key, $value, $metadata)
    {
        if (isset($metadata->associationMappings[$key]))
            $value = $this->entityManager
                ->getReference($metadata->associationMappings[$key]['targetEntity'], $value);

        $metadata->setFieldValue($entity, $key, $value);
    }

    private function removeObsoleteObjects($entityList)
    {
        foreach ($entityList as $entity)
            $this->entityManager->remove($entity);
    }
}
