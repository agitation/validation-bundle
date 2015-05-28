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
use Agit\CoreBundle\Pluggable\Strategy\Seed\SeedProcessorFactory;

/**
 * This class is used by pluggable services themselves to generate listeners
 * for their expected objects/seeds. Use CombinedListenerFactory to create instances.
 */
class CombinedListener
{
    private $ObjectProcessorFactory;

    private $SeedProcessorFactory;

    private $registrationTag;

    private $parentClass;

    private $entityNameList;

    private $seedDeleteObsolete;

    private $seedUpdateExisting;

    public function __construct(
        ObjectProcessorFactory $ObjectProcessorFactory,
        SeedProcessorFactory $SeedProcessorFactory,
        $registrationTag,
        $parentClass,
        array $entityNameList,
        $seedDeleteObsolete,
        $seedUpdateExisting)
    {
        $this->ObjectProcessorFactory = $ObjectProcessorFactory;
        $this->SeedProcessorFactory = $SeedProcessorFactory;
        $this->registrationTag = $registrationTag;
        $this->parentClass = $parentClass;
        $this->entityNameList = $entityNameList;
        $this->seedDeleteObsolete = $seedDeleteObsolete;
        $this->seedUpdateExisting = $seedUpdateExisting;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $RegistrationEvent)
    {
        $RegistrationEvent->registerProcessor(new CombinedProcessor(
            $this->ObjectProcessorFactory,
            $this->SeedProcessorFactory,
            $this->registrationTag,
            $this->parentClass,
            $this->entityNameList,
            $this->seedDeleteObsolete,
            $this->seedUpdateExisting
        ));
    }
}
