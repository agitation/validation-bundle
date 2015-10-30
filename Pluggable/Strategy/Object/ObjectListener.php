<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Object;

use Agit\CoreBundle\Pluggable\PluggableServiceRegistrationEvent;

/**
 * This class is used by pluggable services themselves to generate listeners
 * for their expected objects. Use ObjectListenerFactory to create instances.
 */
class ObjectListener
{
    private $processorFactory;

    private $parentClass;

    public function __construct(ObjectProcessorFactory $processorFactory, $registrationTag, $parentClass)
    {
        $this->processorFactory = $processorFactory;
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $registrationEvent)
    {
        $registrationEvent->registerProcessor(
            $this->processorFactory->create(
                $this->registrationTag,
                $this->parentClass
        ));
    }
}
