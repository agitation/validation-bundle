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
use Agit\CommonBundle\Exception\InternalErrorException;

class CacheLoader
{
    private $cacheProvider;

    private $registrationTag;

    public function __construct(CacheProvider $cacheProvider, $registrationTag)
    {
        $this->cacheProvider = $cacheProvider;
        $this->registrationTag = $registrationTag;
    }

    public function load()
    {
        return $this->cacheProvider->fetch($this->registrationTag) ?: [];
    }
}
