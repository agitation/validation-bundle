<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Cache;

use Agit\CoreBundle\Pluggable\PluggableServiceRegistrationEvent;
use Agit\CoreBundle\Pluggable\Strategy\Cache\CacheProcessorFactory;

/**
 * Used by the various pluggable services that use the cache strategy.
 * Use the CacheAwareServiceListenerFactory to generate instances.
 */
class CacheAwareServiceListener
{
    private $ProcessorFactory;

    public function __construct(CacheProcessorFactory $ProcessorFactory, $registrationTag)
    {
        $this->ProcessorFactory = $ProcessorFactory;
        $this->registrationTag = $registrationTag;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $RegistrationEvent)
    {
        $RegistrationEvent->registerProcessor(
            $this->ProcessorFactory->create(
                $this->registrationTag
        ));
    }
}
