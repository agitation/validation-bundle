<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

/**
 * Creates cache-aware service listeners.
 */
class CacheAwareServiceListenerFactory
{
    public function __construct(CacheProcessorFactory $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }

    public function create($registrationTag)
    {
        return new CacheAwareServiceListener($this->processorFactory, $registrationTag);
    }
}
