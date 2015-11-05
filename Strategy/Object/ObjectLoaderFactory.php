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
use Symfony\Component\DependencyInjection\ContainerInterface;

class ObjectLoaderFactory
{
    private $cacheProvider;

    private $container;

    public function __construct(CacheProvider $cacheProvider, ContainerInterface $container = null)
    {
        $this->cacheProvider = $cacheProvider;
        $this->container = $container;
    }

    public function create($registrationTag)
    {
        return new ObjectLoader($this->cacheProvider, $this->container, $registrationTag);
    }
}
