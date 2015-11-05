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
use Agit\PluggableBundle\Strategy\ProcessorFactoryInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

class SeedProcessorFactory implements ProcessorFactoryInterface
{
    private $entityManager;

    private $entityValidator;

    private $container;

    public function __construct(EntityManager $entityManager, Validator $entityValidator, ContainerInterface $container = null)
    {
        $this->entityManager = $entityManager;
        $this->entityValidator = $entityValidator;
        $this->container = $container;
    }

    public function createPluggableService(array $attributes)
    {
        return new SeedPluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new SeedProcessor($this->entityManager, $this->entityValidator, $this->container, $pluggableService);
    }
}
