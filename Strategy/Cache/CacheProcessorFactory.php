<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Agit\CommonBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorFactoryInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

/**
 * Processes registered objects
 */
class CacheProcessorFactory implements ProcessorFactoryInterface
{
    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
    }

    public function createPluggableService(array $attributes)
    {
        return new CachePluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new CacheProcessor($this->cacheProvider, $this->container, $pluggableService);
    }
}
