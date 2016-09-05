<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

use Agit\BaseBundle\Pluggable\Object\ObjectLoader;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityLoader extends ObjectLoader
{
    private $registrationTag;

    private $entityName;

    private $entityManager;

    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, EntityManager $entityManager, ContainerInterface $container = null, $registrationTag, $entityName)
    {
        parent::__construct($cacheProvider, $container, $registrationTag);

        $this->cacheProvider = $cacheProvider;
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->registrationTag = $registrationTag;
        $this->entityName = $entityName;
    }

    protected function loadObject($id)
    {
        parent::loadObject($id);

        $entity = $this->entityManager->find($this->entityName, $id);
        $this->objects[$id]['instance']->setEntity($entity);
    }
}
