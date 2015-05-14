<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Combined;

use Agit\CoreBundle\Pluggable\PluggableServiceRegistrationEvent;
use Agit\CoreBundle\Pluggable\Strategy\Object\ObjectProcessorFactory;
use Agit\CoreBundle\Pluggable\Strategy\Fixture\FixtureProcessorFactory;

/**
 * This class is used by pluggable services themselves to generate listeners
 * for their expected objects/fixtures. Use CombinedListenerFactory to create instances.
 */
class CombinedListener
{
    private $ObjectProcessorFactory;

    private $FixtureProcessorFactory;

    private $registrationTag;

    private $parentClass;

    private $entityNameList;

    private $fixtureDeleteObsolete;

    private $fixtureUpdateExisting;

    public function __construct(
        ObjectProcessorFactory $ObjectProcessorFactory,
        FixtureProcessorFactory $FixtureProcessorFactory,
        $registrationTag,
        $parentClass,
        array $entityNameList,
        $fixtureDeleteObsolete,
        $fixtureUpdateExisting)
    {
        $this->ObjectProcessorFactory = $ObjectProcessorFactory;
        $this->FixtureProcessorFactory = $FixtureProcessorFactory;
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
        $this->entityNameList = $entityNameList;
        $this->fixtureDeleteObsolete = $fixtureDeleteObsolete;
        $this->fixtureUpdateExisting = $fixtureUpdateExisting;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $RegistrationEvent)
    {
        $RegistrationEvent->registerProcessor(new CombinedProcessor(
            $this->ObjectProcessorFactory,
            $this->FixtureProcessorFactory,
            $this->registrationTag,
            $this->parentClass,
            $this->entityNameList,
            $this->fixtureDeleteObsolete,
            $this->fixtureUpdateExisting
        ));
    }
}
