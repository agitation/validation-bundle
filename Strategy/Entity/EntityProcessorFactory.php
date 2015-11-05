<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Entity;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\PluggableBundle\Strategy\ProcessorFactoryInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;
use Agit\PluggableBundle\Strategy\Object\ObjectProcessorFactory;
use Agit\PluggableBundle\Strategy\Seed\SeedProcessorFactory;

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
