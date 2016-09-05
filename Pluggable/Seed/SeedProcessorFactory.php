<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Seed;

use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\ProcessorFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeedProcessorFactory implements ProcessorFactoryInterface
{
    private $entityManager;

    private $container;

    public function __construct(EntityManager $entityManager, ContainerInterface $container = null)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function createPluggableService(array $attributes)
    {
        return new SeedPluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new SeedProcessor($this->entityManager, $this->container, $pluggableService);
    }
}
