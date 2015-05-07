<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Agit\CoreBundle\Exception\InternalErrorException;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Creates an object processor instance.
 */
class ObjectProcessorFactory
{
    public function __construct(CacheProvider $CacheProvider)
    {
        $this->CacheProvider = $CacheProvider;
    }

    /**
     * @param string $parentClass name of the class/interface which plugin objects should inherit/implement.
     */
    public function create($registrationTag, $parentClass)
    {
        return new ObjectProcessor($this->CacheProvider, $registrationTag, $parentClass);
    }
}
