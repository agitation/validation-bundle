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
    private $CacheProvider;

    private $Container;

    public function __construct(CacheProvider $CacheProvider, ContainerInterface $Container)
    {
        $this->CacheProvider = $CacheProvider;
        $this->Container = $Container;
    }

    public function create($registrationTag, $allowServiceInjection = false)
    {
        return new ObjectLoader($this->CacheProvider, $registrationTag, $allowServiceInjection ? $this->Container : null);
    }
}
