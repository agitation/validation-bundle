<?php
/**
 * @package    agitation/core
 * @link       http://github.com/agitation/AgitCoreBundle
 * @author     Alex Günsche <http://www.agitsol.com/>
 * @copyright  2012-2015 AGITsol GmbH
 * @license    http://opensource.org/licenses/MIT
 */

namespace Agit\CoreBundle\Pluggable\Strategy\Fixture;

use Agit\CoreBundle\Pluggable\PluggableServiceRegistrationEvent;


/**
 * Reusable listener for pluggable fixtures.
 */
class FixtureListener
{
    private $ProcessorFactory;

    private $entityName;

    private $priority;

    private $removeObsolete;

    private $updateExisting;

    public function __construct(FixtureProcessorFactory $ProcessorFactory, $entityName, $priority, $removeObsolete, $updateExisting)
    {
        $this->ProcessorFactory = $ProcessorFactory;
        $this->entityName = $entityName;
        $this->priority = $priority;
        $this->removeObsolete = (bool)$removeObsolete;
        $this->updateExisting = (bool)$updateExisting;
    }

    public function onRegistration(PluggableServiceRegistrationEvent $RegistrationEvent)
    {
        $RegistrationEvent->registerProcessor(
            $this->ProcessorFactory->create(
                $this->entityName,
                $this->priority,
                $this->removeObsolete,
                $this->updateExisting
        ));
    }
}
