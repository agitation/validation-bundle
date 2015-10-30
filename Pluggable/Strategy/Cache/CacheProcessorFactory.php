<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

use Agit\CoreBundle\Exception\InternalErrorException;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Creates an object processor instance.
 */
class CacheProcessorFactory
{
    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @param string $registrationTag name of the service
     */
    public function create($registrationTag)
    {
        return new CacheProcessor($this->cacheProvider, $registrationTag);
    }
}
