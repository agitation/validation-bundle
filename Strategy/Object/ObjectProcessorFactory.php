<?php
/**
 * @package    agitation/pluggable
 * @link       http://github.com/agitation/AgitPluggableBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\PluggableBundle\Strategy\Object;

use Doctrine\Common\Cache\CacheProvider;
use Agit\CoreBundle\Exception\InternalErrorException;
use Agit\PluggableBundle\Strategy\ProcessorFactoryInterface;
use Agit\PluggableBundle\Strategy\PluggableServiceInterface;

/**
 * Processes registered objects
 */
class ObjectProcessorFactory implements ProcessorFactoryInterface
{
    private $cacheProvider;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function createPluggableService(array $attributes)
    {
        return new ObjectPluggableService($attributes);
    }

    public function createProcessor(PluggableServiceInterface $pluggableService)
    {
        return new ObjectProcessor($this->cacheProvider, $pluggableService);
    }
}
