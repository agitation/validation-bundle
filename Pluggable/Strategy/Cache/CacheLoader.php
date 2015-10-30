<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Provides registered objects to the pluggable service at run-time.
 */
class CacheLoader
{
    private $cacheProvider;

    private $registrationTag;

    public function __construct(CacheProvider $cacheProvider, $registrationTag)
    {
        $this->cacheProvider = $cacheProvider;
        $this->registrationTag = $registrationTag;
    }

    public function loadPlugins()
    {
        return $this->cacheProvider->fetch($this->registrationTag) ?: [];
    }
}
