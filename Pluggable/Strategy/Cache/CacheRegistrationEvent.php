<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

use Symfony\Component\EventDispatcher\Event;
use Agit\CoreBundle\Pluggable\Strategy\ProcessorInterface;

/**
 * Data container for cachable objects.
 */
class CacheRegistrationEvent extends Event
{
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function createContainer()
    {
        return new CacheData();
    }

    public function register(CacheData $cacheData, $priority)
    {
        return $this->processor->register($cacheData, $priority);
    }
}