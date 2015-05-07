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

class ObjectLoaderFactory
{
    private $CacheProvider;

    public function __construct(CacheProvider $CacheProvider)
    {
        $this->CacheProvider = $CacheProvider;
    }

    public function create($registrationTag)
    {
        return new ObjectLoader($this->CacheProvider, $registrationTag);
    }
}
