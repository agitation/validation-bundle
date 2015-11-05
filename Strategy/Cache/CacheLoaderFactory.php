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

class CacheLoaderFactory
{
    private $cacheProvider;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function create($registrationTag)
    {
        return new CacheLoader($this->cacheProvider, $registrationTag);
    }
}
