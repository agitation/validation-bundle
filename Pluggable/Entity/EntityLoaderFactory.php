<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityLoaderFactory
{
    private $entityManager;

    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, EntityManager $entityManager, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function create($registrationTag, $entityName)
    {
        return new EntityLoader($this->cacheProvider, $this->entityManager, $this->container, $registrationTag, $entityName);
    }
}
