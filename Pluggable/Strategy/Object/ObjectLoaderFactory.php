<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ObjectLoaderFactory
{
    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
    }

    public function create($registrationTag, $allowServiceInjection = false)
    {
        return new ObjectLoader($this->cacheProvider, $registrationTag, $allowServiceInjection ? $this->container : null);
    }
}
