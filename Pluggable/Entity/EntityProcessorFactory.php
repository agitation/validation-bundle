<?php

/*
 * @package    agitation/base-bundle
 * @link       http://github.com/agitation/base-bundle
 * @author     Alexander Günsche
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\BaseBundle\Pluggable\Entity;

use Agit\BaseBundle\Pluggable\Object\ObjectProcessorFactory;
use Agit\BaseBundle\Pluggable\PluggableServiceInterface;
use Agit\BaseBundle\Pluggable\ProcessorFactoryInterface;
use Agit\BaseBundle\Pluggable\Seed\SeedProcessorFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EntityProcessorFactory implements ProcessorFactoryInterface
{
    private $objectProcessorFactory;

    private $seedProcessorFactory;

    private $container;

    public function __construct(ObjectProcessorFactory $objectProcessorFactory, SeedProcessorFactory $seedProcessorFactory, ContainerInterface $container = null)
    {
        $this->objectProcessorFactory = $objectProcessorFactory;
        $this->seedProcessorFactory = $seedProcessorFactory;
        $this->container = $container;
    }

    public function createPluggableService(array $attributes)
    {
        return new EntityPluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new EntityProcessor($this->objectProcessorFactory, $this->seedProcessorFactory, $this->container, $pluggableService);
    }
}
