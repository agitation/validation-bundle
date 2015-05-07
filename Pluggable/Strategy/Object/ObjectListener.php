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
    private $ProcessorFactory;

    private $parentClass;

    public function __construct(ObjectProcessorFactory $ProcessorFactory, $registrationTag, $parentClass)
    {
        $this->ProcessorFactory = $ProcessorFactory;
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $RegistrationEvent)
    {
        $RegistrationEvent->registerProcessor(
            $this->ProcessorFactory->create(
                $this->registrationTag,
                $this->parentClass
        ));
    }
}
